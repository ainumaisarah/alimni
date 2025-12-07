<x-guest-layout>
    <div class="auth-container">
        <!-- Logo -->
        <div class="platform-logo mb-6">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mx-auto">
        </div>

        <div class="label mb-6 text-black">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        @session('status')
            <div class="success-alert mb-4">
                {{ $value }}
            </div>
        @endsession

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="block">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="auth-actions flex justify-end mt-4">
                <x-button type="submit" class="login-button">
                    {{ __('Email Password Reset Link') }}
                </x-button>
            </div>
        </form>
    </div>
</x-guest-layout>
