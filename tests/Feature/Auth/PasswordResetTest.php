<?php

namespace Tests\Feature\Auth;

use App\Mail\PasswordResetOtpMail;
use App\Services\PasswordResetOtpService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * "Forgot password" with an emailed 6-digit verification code:
 *   request code -> verify code -> choose new password.
 *
 * Builds its own tiny schema on the in-memory sqlite DB from phpunit.xml, so it
 * never touches the real MySQL databases.
 */
class PasswordResetTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('admins', function ($t) {
            $t->bigIncrements('n_role_id');
            $t->unsignedBigInteger('n_employee_id')->nullable();
            $t->string('c_name');
            $t->string('c_username')->nullable();
            $t->string('c_password')->nullable();
            $t->string('c_status')->nullable();
            $t->timestamps();
            $t->text('initial_password')->nullable();
            $t->timestamp('initial_password_expires_at')->nullable();
        });

        Schema::create('employee_masters', function ($t) {
            $t->bigIncrements('n_employee_id');
            $t->string('c_employee_email')->nullable();
            $t->string('personal_email')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });

        (require database_path('migrations/2026_09_24_100000_create_password_reset_otps_table.php'))->up();

        DB::table('admins')->insert([
            'c_name' => 'Anu',
            'c_username' => 'anu@spc.com',
            'c_password' => Hash::make('OldPass@123'),
            'c_status' => 'Active',
            'initial_password' => 'stale-secret',
            'initial_password_expires_at' => now()->addDay(),
        ]);
    }

    private function requestCode(string $identifier = 'anu@spc.com'): string
    {
        Mail::fake();

        $this->post('/forgot-password', ['email' => $identifier])
            ->assertRedirect(route('password.otp'));

        $code = null;
        Mail::assertSent(PasswordResetOtpMail::class, function ($mail) use (&$code) {
            $code = $mail->code;

            return true;
        });

        return $code;
    }

    public function test_forgot_password_page_shows_the_spc_logo(): void
    {
        $this->get('/forgot-password')
            ->assertOk()
            ->assertSee('dist/images/logos/spclogo.png', false)
            ->assertDontSee('centreal', false)
            ->assertSee('Send Verification Code');
    }

    public function test_a_code_is_emailed_and_only_its_hash_is_stored(): void
    {
        $code = $this->requestCode();

        $this->assertMatchesRegularExpression('/^\d{6}$/', $code);
        Mail::assertSent(PasswordResetOtpMail::class, fn ($m) => $m->hasTo('anu@spc.com'));

        $row = DB::table('password_reset_otps')->first();
        $this->assertNotNull($row);
        $this->assertNotSame($code, $row->otp_hash);
        $this->assertStringNotContainsString($code, json_encode($row));
    }

    public function test_the_email_renders_with_code_and_embedded_logo(): void
    {
        config(['mail.default' => 'array']);

        \Illuminate\Support\Facades\Mail::to('anu@spc.com')->send(new PasswordResetOtpMail('Anu', '482913', 10));

        $sent = app('mailer')->getSymfonyTransport()->messages()->first()->getOriginalMessage();

        $this->assertStringContainsString('482913', $sent->getSubject());
        $this->assertStringContainsString('482913', $sent->getHtmlBody());
        $this->assertStringContainsString('cid:', $sent->getHtmlBody());   // logo embedded, not a remote URL
        $this->assertCount(1, $sent->getAttachments());
    }

    public function test_unknown_account_shows_a_clear_error_and_no_email(): void
    {
        Mail::fake();

        $this->from('/forgot-password')->post('/forgot-password', ['email' => 'nobody@spc.com'])
            ->assertRedirect('/forgot-password')
            ->assertSessionHasErrors(['email' => 'No account was found with this email / username. Please check it and try again.']);

        Mail::assertNothingSent();
    }

    public function test_inactive_account_shows_a_clear_error_and_no_email(): void
    {
        DB::table('admins')->update(['c_status' => 'Inactive']);
        Mail::fake();

        $this->post('/forgot-password', ['email' => 'anu@spc.com'])
            ->assertSessionHasErrors(['email' => 'This account is inactive. Please contact your administrator.']);

        Mail::assertNothingSent();
    }

    public function test_account_without_a_valid_email_shows_a_clear_error(): void
    {
        DB::table('admins')->update(['c_username' => 'anu_k']);
        Mail::fake();

        $this->post('/forgot-password', ['email' => 'anu_k'])
            ->assertSessionHasErrors(['email' => 'No valid email address is linked to this account. Please contact your administrator.']);

        Mail::assertNothingSent();
    }

    public function test_a_mail_server_failure_is_reported_to_the_person(): void
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP connection refused'));

        $this->post('/forgot-password', ['email' => 'anu@spc.com'])
            ->assertSessionHasErrors(['email' => 'We could not send the email. The mail server settings may be incorrect — please contact your administrator.']);

        $this->assertSame(0, DB::table('password_reset_otps')->count());
    }

    public function test_with_error_messages_switched_off_unknown_accounts_get_a_generic_response(): void
    {
        config(['auth.password_reset_show_errors' => false]);
        Mail::fake();

        $this->post('/forgot-password', ['email' => 'nobody@spc.com'])
            ->assertRedirect(route('password.otp'))
            ->assertSessionHas('status', 'If that account exists, a 6-digit verification code has been sent to its email address.');

        Mail::assertNothingSent();

        $this->post('/forgot-password/verify', ['code' => '123456'])->assertSessionHasErrors('code');
    }

    public function test_login_page_displays_login_errors(): void
    {
        $this->from('/login')->post('/login', ['email' => 'anu@spc.com', 'password' => 'wrong'])
            ->assertSessionHasErrors('email');

        $this->get('/login')->assertSee('Invalid username or password.');
    }

    public function test_the_code_is_sent_to_the_employee_email_when_the_username_is_not_an_email(): void
    {
        DB::table('employee_masters')->insert(['n_employee_id' => 7, 'c_employee_email' => 'anu.work@spc.com']);
        DB::table('admins')->update(['c_username' => 'anu_k', 'n_employee_id' => 7]);

        $this->requestCode('anu_k');

        Mail::assertSent(PasswordResetOtpMail::class, fn ($m) => $m->hasTo('anu.work@spc.com'));
    }

    public function test_reset_form_is_locked_until_the_code_is_verified(): void
    {
        $this->get('/reset-password')->assertRedirect(route('password.request'));

        $this->requestCode();

        $this->get('/reset-password')->assertRedirect(route('password.request'));

        $this->post('/reset-password', [
            'password' => 'BrandNew@123', 'password_confirmation' => 'BrandNew@123',
        ])->assertRedirect(route('password.request'));

        $this->assertTrue(Hash::check('OldPass@123', DB::table('admins')->value('c_password')));
    }

    public function test_full_flow_resets_the_password(): void
    {
        $code = $this->requestCode();

        $this->get('/forgot-password/verify')->assertOk()->assertSee('a***@spc.com', false);

        $this->post('/forgot-password/verify', ['code' => $code])->assertRedirect(route('password.reset'));

        $this->get('/reset-password')->assertOk()->assertSee('Create new password');

        $this->post('/reset-password', [
            'password' => 'BrandNew@123', 'password_confirmation' => 'BrandNew@123',
        ])->assertRedirect(route('login'))->assertSessionHas('status');

        $admin = DB::table('admins')->first();
        $this->assertTrue(Hash::check('BrandNew@123', $admin->c_password));
        $this->assertNull($admin->initial_password);
        $this->assertNull($admin->initial_password_expires_at);
        $this->assertSame(0, DB::table('password_reset_otps')->count());

        // the login page confirms the change...
        $this->get('/login')->assertOk()->assertSee('Your password has been reset');

        // ...and the flow cannot be replayed
        $this->get('/reset-password')->assertRedirect(route('password.request'));
    }

    public function test_wrong_codes_are_counted_and_the_code_locks_after_five(): void
    {
        $code = $this->requestCode();
        $wrong = $code === '000000' ? '111111' : '000000';

        $this->post('/forgot-password/verify', ['code' => $wrong])
            ->assertSessionHasErrors(['code' => 'Incorrect code. You have 4 attempt(s) left.']);

        foreach (range(1, 3) as $i) {
            $this->post('/forgot-password/verify', ['code' => $wrong]);
        }

        $this->post('/forgot-password/verify', ['code' => $wrong])
            ->assertRedirect(route('password.request'))
            ->assertSessionHasErrors('email');

        // even the right code no longer works
        $this->post('/forgot-password/verify', ['code' => $code])->assertRedirect(route('password.request'));
        $this->assertSame(0, DB::table('password_reset_otps')->count());
    }

    public function test_expired_codes_are_rejected(): void
    {
        $code = $this->requestCode();

        $this->travel(PasswordResetOtpService::OTP_TTL_MINUTES + 1)->minutes();

        $this->post('/forgot-password/verify', ['code' => $code])
            ->assertSessionHasErrors(['code' => 'This code has expired. Please request a new one.']);
    }

    public function test_verification_expires_if_the_password_is_not_set_in_time(): void
    {
        $code = $this->requestCode();
        $this->post('/forgot-password/verify', ['code' => $code])->assertRedirect(route('password.reset'));

        $this->travel(PasswordResetOtpService::RESET_WINDOW_MINUTES + 1)->minutes();

        $this->get('/reset-password')->assertRedirect(route('password.request'));
    }

    public function test_resend_respects_the_cooldown_and_issues_a_new_code(): void
    {
        $first = $this->requestCode();

        $this->post('/forgot-password/resend')->assertSessionHasErrors('code');

        $this->travel(PasswordResetOtpService::RESEND_COOLDOWN_SECONDS + 1)->seconds();

        Mail::fake();
        $this->post('/forgot-password/resend')->assertSessionHas('status');
        Mail::assertSent(PasswordResetOtpMail::class, 1);

        // only one live code per account
        $this->assertSame(1, DB::table('password_reset_otps')->count());
    }

    public function test_new_password_must_be_strong_confirmed_and_different(): void
    {
        $code = $this->requestCode();
        $this->post('/forgot-password/verify', ['code' => $code]);

        $this->post('/reset-password', ['password' => 'short', 'password_confirmation' => 'short'])
            ->assertSessionHasErrors('password');

        $this->post('/reset-password', ['password' => 'BrandNew@123', 'password_confirmation' => 'nope'])
            ->assertSessionHasErrors('password');

        $this->post('/reset-password', ['password' => 'OldPass@123', 'password_confirmation' => 'OldPass@123'])
            ->assertSessionHasErrors('password');

        $this->assertTrue(Hash::check('OldPass@123', DB::table('admins')->value('c_password')));
    }
}
