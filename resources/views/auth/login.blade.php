<x-guest-layout>
    <div class="auth-container">
        <div class="platform-logo mb-6">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mx-auto">
        </div>

        <x-validation-errors class="mb-4 text-red-500 bg-red-100 rounded-lg p-2 text-center" />

        @if(session('success'))
            <div class="success-alert mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="username" value="{{ __('Username') }}" />
                <x-input id="username" class="block mt-1 w-full px-4 py-2 rounded-lg border border-white/30 bg-white/5 text-white focus:outline-none focus:border-red-700 focus:ring-1 focus:ring-red-700 transition" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
            </div>

            <div>
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full px-4 py-2 rounded-lg border border-white/30 bg-white/5 text-white focus:outline-none focus:border-red-700 focus:ring-1 focus:ring-red-700 transition" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="flex items-center mt-4 gap-2">
                <x-checkbox id="remember_me" name="remember" />
                <label for="remember_me" class="text-gray-200 text-sm">{{ __('Remember me') }}</label>
            </div>

            <div class="auth-actions flex items-center mt-4 w-full">
    <div class="flex-1">
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-sm text-gray-200 hover:text-red-400">
                {{ __('Forgot your password?') }}
            </a>
        @endif
    </div>

    <x-button type="submit" class="login-button items-center">
        {{ __('Log in') }}
    </x-button>
</div>



        </form>
    </div>
</x-guest-layout>
