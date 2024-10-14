<?php

namespace App\Http\Requests\Auth;

use App\Models\Tenant\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Authentication extends FormRequest
{
    protected string $authType;

    public function __construct()
    {
        parent::__construct();
        $this->authType = $this->credentials();
    }

    public function credentials(): string
    {

        $usernameOrEmail = request()->input('login');
        if (is_numeric($usernameOrEmail)) {
            $fieldType = 'phone';
        } else {
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
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return match ($this->authType) {
            'email' => [
                'login' => ['required', 'email'],
                'password' => ['required', 'string'],
                'travel_code' => ['nullable', 'string'],
            ],
            'username' => [
                'login' => ['required', 'string', 'max:50'],
                'password' => ['required', 'string',],
                'travel_code' => ['nullable', 'string',],
            ],
            'phone' => [
                'login' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/',],
                'password' => ['required', 'string',],
                'travel_code' => ['nullable', 'string',],
            ],
            default => [],
        };
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        try {
            if (request()->get('travel_code')) {
                $tenant = Tenant::query()
                    ->select('id')
                    ->where('is_active', true)
                    ->firstWhere('bcn', request()->get('travel_code'));

                if (!$tenant){
                    throw ValidationException::withMessages([
                        'email' => trans('auth.failed'),
                    ]);
                }

                request()->merge(['tenant_id' => $tenant?->id]);

                if (!Auth::attempt(request()->only($this->authType, 'password', 'tenant_id'), request()->boolean('remember'))) {
                    RateLimiter::hit($this->throttleKey());
                    throw ValidationException::withMessages([
                        'email' => trans('auth.failed'),
                    ]);
                }
            }else{
                request()->merge(['tenant_id' => null]);
                if (!Auth::attempt(request()->only($this->authType, 'password', 'tenant_id'), request()->boolean('remember'))) {
                    RateLimiter::hit($this->throttleKey());
                    throw ValidationException::withMessages([
                        'email' => trans('auth.failed'),
                    ]);
                }
            }
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
        }
        $this->saveLastLogin();

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
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
    public function throttleKey(): string
    {
        return Str::lower(request()->input('username')) . '|' . request()->ip();
    }

    /**
     * @return void
     */
    private function saveLastLogin(): void
    {
        $user = User::query()->find(\auth()->user()->id);
        $user->last_login_at = Carbon::now();
        $user->save();
    }
}
