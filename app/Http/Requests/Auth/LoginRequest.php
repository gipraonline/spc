<?php

namespace App\Http\Requests\Auth;

use App\Models\Admin;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                // 'email',
                // function ($attribute, $value, $fail) {
                //     if (!str_ends_with($value, '@centralbazar.com')) {
                //         $fail('Only centralbazar.com emails are allowed.');
                //     }
                // },
            ],
            'password' => ['required'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = ['c_username' => $this->email, 'password' => $this->password];

        // Only accounts whose status is "Active" may sign in. HR offboarding
        // sets admins.c_status = 'Inactive', which must end SPC access too.
        if (! Auth::attempt($credentials + ['c_status' => Admin::STATUS_ACTIVE], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            // Correct credentials but a deactivated account: say so, rather
            // than the misleading "invalid username or password". Only
            // revealed once the password has been proven correct.
            if (Auth::validate($credentials)) {
                throw ValidationException::withMessages([
                    'email' => 'This account is inactive. Please contact your administrator.',
                ]);
            }

            throw ValidationException::withMessages([
               // 'email' => trans('auth.failed'),
                 'email' => 'Invalid username or password.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->string('email')).'|'.$this->ip()
        );
    }
}
