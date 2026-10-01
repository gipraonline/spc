<?php

namespace App\Services;

use App\Mail\PasswordResetOtpMail;
use App\Models\Admin;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Email-OTP based password recovery for SPC admins.
 *
 * Flow: request code -> verify code -> set new password.
 */
class PasswordResetOtpService
{
    public const TABLE = 'password_reset_otps';

    public const OTP_LENGTH = 6;
    public const OTP_TTL_MINUTES = 10;          // how long a code is valid
    public const MAX_ATTEMPTS = 5;              // wrong guesses before the code is burned
    public const RESEND_COOLDOWN_SECONDS = 60;  // min gap between two emails
    public const RESET_WINDOW_MINUTES = 15;     // time allowed to set the password after verifying

    /** Find an active admin by the username / email typed on the form. */
    public function findAdmin(string $identifier, bool $activeOnly = true): ?Admin
    {
        $identifier = Str::lower(trim($identifier));

        if ($identifier === '') {
            return null;
        }

        return Admin::query()
            ->whereRaw('LOWER(c_username) = ?', [$identifier])
            ->when($activeOnly, fn ($q) => $q->where('c_status', 'Active'))
            ->first();
    }

    /**
     * Where the code should be delivered: the login email itself when it is a
     * valid address, otherwise the linked employee's official / personal email.
     */
    public function resolveEmail(Admin $admin): ?string
    {
        $employee = $admin->employee;

        foreach ([$admin->c_username, $employee?->c_employee_email, $employee?->personal_email] as $candidate) {
            $candidate = trim((string) $candidate);

            if ($candidate !== '' && filter_var($candidate, FILTER_VALIDATE_EMAIL)) {
                return $candidate;
            }
        }

        return null;
    }

    /** j***@gmail.com */
    public static function maskEmail(string $email): string
    {
        [$local, $domain] = array_pad(explode('@', $email, 2), 2, '');

        if ($domain === '') {
            return Str::mask($email, '*', 1);
        }

        return mb_substr($local, 0, 1).str_repeat('*', max(mb_strlen($local) - 1, 3)).'@'.$domain;
    }

    /** Seconds the person still has to wait before another email may be sent. */
    public function secondsUntilResend(int $adminId): int
    {
        $row = $this->pendingRow($adminId);

        if (! $row || ! $row->last_sent_at) {
            return 0;
        }

        $readyAt = Carbon::parse($row->last_sent_at)->addSeconds(self::RESEND_COOLDOWN_SECONDS);

        return max(0, (int) ceil(now()->diffInSeconds($readyAt, false)));
    }

    /**
     * Generate a fresh code (replacing any earlier one) and email it.
     *
     * @throws \Throwable when the mail could not be delivered
     */
    public function send(Admin $admin, string $email, ?string $ip = null): void
    {
        $adminId = (int) $admin->getKey();
        $code = str_pad((string) random_int(0, 10 ** self::OTP_LENGTH - 1), self::OTP_LENGTH, '0', STR_PAD_LEFT);

        DB::table(self::TABLE)->where('admin_id', $adminId)->delete();

        $id = DB::table(self::TABLE)->insertGetId([
            'admin_id' => $adminId,
            'email' => $email,
            'otp_hash' => $this->hash($adminId, $code),
            'attempts' => 0,
            'expires_at' => now()->addMinutes(self::OTP_TTL_MINUTES),
            'last_sent_at' => now(),
            'ip_address' => $ip,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        try {
            Mail::to($email)->send(new PasswordResetOtpMail($admin->c_name, $code, self::OTP_TTL_MINUTES));
        } catch (\Throwable $e) {
            DB::table(self::TABLE)->where('id', $id)->delete();

            throw $e;
        }
    }

    /**
     * Check a submitted code.
     *
     * @return array{status: 'ok'|'invalid'|'expired'|'locked', left: int}
     */
    public function verify(int $adminId, string $code): array
    {
        $row = $this->pendingRow($adminId);

        if (! $row) {
            return ['status' => 'expired', 'left' => 0];
        }

        if (now()->greaterThan(Carbon::parse($row->expires_at))) {
            $this->clear($adminId);

            return ['status' => 'expired', 'left' => 0];
        }

        if ($row->attempts >= self::MAX_ATTEMPTS) {
            $this->clear($adminId);

            return ['status' => 'locked', 'left' => 0];
        }

        if (hash_equals($row->otp_hash, $this->hash($adminId, $code))) {
            DB::table(self::TABLE)->where('id', $row->id)->update([
                'verified_at' => now(),
                'updated_at' => now(),
            ]);

            return ['status' => 'ok', 'left' => self::MAX_ATTEMPTS - $row->attempts];
        }

        $attempts = $row->attempts + 1;

        if ($attempts >= self::MAX_ATTEMPTS) {
            $this->clear($adminId);

            return ['status' => 'locked', 'left' => 0];
        }

        DB::table(self::TABLE)->where('id', $row->id)->update([
            'attempts' => $attempts,
            'updated_at' => now(),
        ]);

        return ['status' => 'invalid', 'left' => self::MAX_ATTEMPTS - $attempts];
    }

    /** True when this admin has verified a code recently enough to set a password. */
    public function isVerified(int $adminId): bool
    {
        $row = DB::table(self::TABLE)
            ->where('admin_id', $adminId)
            ->whereNotNull('verified_at')
            ->latest('id')
            ->first();

        return $row
            && Carbon::parse($row->verified_at)->addMinutes(self::RESET_WINDOW_MINUTES)->isFuture();
    }

    /** Save the new password and burn every code for the account. */
    public function complete(Admin $admin, string $password): void
    {
        DB::transaction(function () use ($admin, $password) {
            Admin::query()->whereKey($admin->getKey())->update([
                'c_password' => bcrypt($password),
                // The one-time "initial password" shown to admins is now stale.
                'initial_password' => null,
                'initial_password_expires_at' => null,
            ]);

            $this->clear((int) $admin->getKey());
        });
    }

    public function clear(int $adminId): void
    {
        DB::table(self::TABLE)->where('admin_id', $adminId)->delete();
    }

    /** Latest code that has not been verified yet. */
    protected function pendingRow(int $adminId): ?object
    {
        return DB::table(self::TABLE)
            ->where('admin_id', $adminId)
            ->whereNull('verified_at')
            ->latest('id')
            ->first();
    }

    protected function hash(int $adminId, string $code): string
    {
        return hash_hmac('sha256', $code, config('app.key').'|otp|'.$adminId);
    }
}
