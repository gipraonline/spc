<?php

namespace App\Services;

use App\Models\AuditRecord;
use App\Models\Hr\AuditLog;
use App\Models\Hr\User as HrUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * One place that writes the audit trail for the whole application.
 *
 *  - SPC (sales / admin) models  -> spc.audit_records
 *  - HR models                   -> spc_hr.audit_logs
 *
 * The "System & Access" page reads both, so every menu shows up in one list.
 *
 * Audit logging must NEVER break the real action, so every write is wrapped
 * in try/catch and failures are only reported to the Laravel log.
 */
class AuditTrail
{
    public const HR = 'hr';

    public const SPC = 'spc';

    /** Attributes that are never written to the log (noise). */
    public const IGNORED = [
        'created_at', 'updated_at', 'deleted_at', 'remember_token',
        'last_login_at', 'last_login_ip',
    ];

    /** Attributes whose value is secret: the log only says "changed". */
    public const SECRET = [
        'password', 'c_password', 'initial_password', 'initial_password_expires_at',
        'bank_account_number', 'account_number', 'bank_ifsc', 'ifsc_code', 'token',
    ];

    private static int $paused = 0;

    /* ------------------------------------------------------------------ */
    /*  Pause / resume (model events only)                                 */
    /* ------------------------------------------------------------------ */

    /** Stop model events from logging (used where a richer manual entry is written). */
    public static function pause(): void
    {
        self::$paused++;
    }

    public static function resume(): void
    {
        self::$paused = max(0, self::$paused - 1);
    }

    public static function withoutAuditing(callable $callback)
    {
        self::pause();
        try {
            return $callback();
        } finally {
            self::resume();
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Model events                                                       */
    /* ------------------------------------------------------------------ */

    /**
     * Called by the Auditable trait.
     *
     * @param  string  $event  created | updated | deleted | restored
     */
    public static function fromModel(Model $model, string $event): void
    {
        if (self::$paused > 0 || self::shouldSkip()) {
            return;
        }

        try {
            $ignored = array_merge(self::IGNORED, $model->auditIgnored());
            $secret = array_merge(self::SECRET, $model->getHidden());
            $action = null;
            $old = null;
            $new = null;

            switch ($event) {
                case 'created':
                    $action = 'CREATE';
                    $new = self::clean($model->getAttributes(), $ignored, $secret, true);
                    break;

                case 'updated':
                    $all = $model->getChanges();

                    // Soft delete / restore done through ->save() (e.g. Sales Orders)
                    if (array_key_exists('deleted_at', $all)) {
                        $snapshot = self::clean($model->getAttributes(), $ignored, $secret, true);
                        if ($model->getAttribute('deleted_at')) {
                            $action = 'DELETE';
                            $old = $snapshot;
                        } else {
                            $action = 'RESTORE';
                            $new = $snapshot;
                        }
                        break;
                    }

                    $changes = array_diff_key($all, array_flip($ignored));
                    if (! $changes) {
                        return;
                    }
                    $before = array_intersect_key($model->getRawOriginal(), $changes);
                    $old = self::clean($before, $ignored, $secret);
                    $new = self::clean($changes, $ignored, $secret);
                    $action = 'UPDATE';
                    break;

                case 'deleted':
                    // Soft deletes through ->delete() fire only "deleted", so log it here.
                    $action = 'DELETE';
                    $old = self::clean($model->getAttributes(), $ignored, $secret, true);
                    break;

                case 'restored':
                    $action = 'RESTORE';
                    $new = self::clean($model->getAttributes(), $ignored, $secret, true);
                    break;
            }

            if ($action === null) {
                return;
            }

            $meta = [
                '_entity' => $model->auditEntity(),
                '_subject' => $model->auditSubject(),
            ];
            $old = $old === null ? null : $old + array_filter($meta);
            $new = $new === null ? null : $new + array_filter($meta);

            self::record(
                $model->auditModule(),
                $action,
                is_numeric($model->getKey()) ? (int) $model->getKey() : null,
                $old,
                $new,
                $model->getConnectionName() === 'spc_hr' ? self::HR : self::SPC
            );
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Manual entries (role permissions, card visibility, bulk actions…)  */
    /* ------------------------------------------------------------------ */

    /**
     * @param  string  $target  AuditTrail::SPC or AuditTrail::HR (which table to write to)
     */
    public static function record(string $module, string $action, ?int $recordId, ?array $old, ?array $new, string $target = self::SPC): void
    {
        if (self::shouldSkip()) {
            return;
        }

        try {
            if ($target === self::HR) {
                $hrUser = self::hrActor();
                $new = self::withActor($new, $old, $hrUser);
                AuditLog::create([
                    'user_id' => $hrUser?->id,
                    'action' => strtoupper($action),
                    'module' => $module,
                    'record_id' => $recordId,
                    'old_value' => $old === null ? null : json_encode($old),
                    'new_value' => $new === null ? null : json_encode($new),
                    'ip_address' => request()->ip(),
                    'created_at' => now(),
                ]);

                return;
            }

            AuditRecord::create([
                'user_id' => Auth::guard('web')->user()?->getKey(),
                'module' => $module,
                'action' => strtoupper($action),
                'record_id' => $recordId,
                'old_values' => $old,
                'new_values' => $new,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Helpers                                                            */
    /* ------------------------------------------------------------------ */

    /** Scheduler / artisan / seeders are not user actions. */
    private static function shouldSkip(): bool
    {
        return app()->runningInConsole() && ! app()->runningUnitTests();
    }

    /** The HR-module user doing this action (HR session, else linked SPC admin). */
    private static function hrActor(): ?HrUser
    {
        if ($id = session('user_id')) {
            return HrUser::find($id);
        }

        return HrUser::findForSpcAdmin(Auth::guard('web')->user());
    }

    /** HR log rows with no linked HR user still remember who did it. */
    private static function withActor(?array $new, ?array $old, ?HrUser $hrUser): ?array
    {
        if ($hrUser || (! $new && ! $old)) {
            return $new;
        }

        $name = Auth::guard('web')->user()?->c_name;

        return $name ? (($new ?? []) + ['_by' => $name]) : $new;
    }

    /**
     * Strip noise, hide secrets, flatten values so they are JSON-safe.
     *
     * @param  bool  $dropEmpty  skip null/'' values (used for full snapshots)
     */
    private static function clean(array $attributes, array $ignored, array $secret, bool $dropEmpty = false): array
    {
        $out = [];

        foreach ($attributes as $key => $value) {
            if (in_array($key, $ignored, true)) {
                continue;
            }
            if (in_array($key, $secret, true)) {
                $out[$key] = '[hidden]';

                continue;
            }
            if ($dropEmpty && ($value === null || $value === '')) {
                continue;
            }
            if (is_array($value) || is_object($value)) {
                $value = json_encode($value);
            }
            if (is_string($value) && mb_strlen($value) > 500) {
                $value = mb_substr($value, 0, 500).'…';
            }
            $out[$key] = $value;
        }

        return $out;
    }
}
