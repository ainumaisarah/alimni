<x-guest-layout>
    <div class="auth-container">
        <!-- Logo -->
        <div class="platform-logo mb-6">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mx-auto">
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="block">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="auth-actions flex justify-end mt-4">
                <x-button type="submit" class="login-button">
                    {{ __('Reset Password') }}
                </x-button>
            </div>
        </form>
    </div>
</x-guest-layout>
