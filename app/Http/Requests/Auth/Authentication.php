<?php

namespace App\Http\Requests\Auth;

use App\Models\Master\Phone;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Authentication extends FormRequest
{
    protected string $authType;

    public function __construct()
    {
        $this->authType = $this->credentials();
    }

    public function credentials(): string
    {

        $usernameOrEmail = request()->input('login');
        if (is_numeric($usernameOrEmail)) {
            $fieldType = 'phone';
        }else{
            $fieldType = filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        }
        request()->merge([$fieldType => $usernameOrEmail]);
        return $fieldType;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        switch ($this->authType) {
            case 'email':
                $rules = [
                    'login' => ['required', 'email'],
                    'password' => ['required', 'string',],
                ];
                break;
            case 'username':
                $rules = [
                    'login' => ['required', 'string', 'max:16'],
                    'password' => ['required', 'string',],
                ];
                break;

            case 'phone':
                $rules = [
                    'login' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/',],
                    'password' => ['required', 'string',],
                ];
                break;

            default:
                $rules = [];
                break;
        }
        return $rules;
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();
        // $this->ensureIsNotRateLimited();

        if (!Auth::attempt(request()->only($this->authType, 'password'), request()->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
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
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower(request()->input('username')) . '|' . request()->ip();
    }
}
