<x-guest-layout>
  <x-auth-card>
    <x-slot name="logo">
      <a href="/">
        <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
      </a>
    </x-slot>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />

    <form method="POST" action="{{ route('auth.login') }}">
      @csrf

      <!-- Email Address -->
      <div>
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input class="block mt-1 w-full" id="email" name="email" type="email" :value="old('email')" required autofocus />
      </div>

      <!-- Password -->
      <div class="mt-4">
        <x-input-label for="password" :value="__('Password')" />
        <x-text-input class="block mt-1 w-full" id="password" name="password" type="password" required autocomplete="current-password" />
      </div>

      <!-- Remember Me -->
      <div class="block mt-4">
        <label class="inline-flex items-center" for="remember_me">
          <input class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            id="remember_me" name="remember" type="checkbox">
          <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
        </label>
      </div>

      <div class="flex items-center justify-end mt-4">
        @if (Route::has('auth.password.request'))
          <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('auth.password.request') }}">
            {{ __('Forgot your password?') }}
          </a>
        @endif

        <x-primary-button class="ml-3">
          {{ __('Log in') }}
        </x-primary-button>
      </div>
    </form>
  </x-auth-card>
</x-guest-layout>
