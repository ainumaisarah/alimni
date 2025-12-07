<x-guest-layout>
    <div class="auth-container">
        <!-- Logo -->
        <div class="platform-logo mb-6">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mx-auto">
        </div>

        <div class="error-alert mb-4">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div>
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" autofocus />
            </div>

            <div class="flex justify-end mt-4">
                <x-button class="ms-4">
                    {{ __('Confirm') }}
                </x-button>
            </div>
        </form>
    </div>
</x-guest-layout>
