<x-guest-layout>
    <div class="auth-container">
        <!-- Logo -->
        <div class="platform-logo mb-6">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mx-auto">
        </div>

        <!-- Validation Errors -->
        <x-validation-errors class="mb-4 text-red-500 bg-red-100 rounded-lg p-2 text-center" />

        <!-- Error Message -->
        @if(session('error'))
            <div class="error-alert mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Full Name --}}
            <div>
                <x-label for="name" value="{{ __('Full Name') }}" />
                <x-input id="name" class="block mt-1 w-full px-4 py-2 rounded-lg border border-white/30 bg-white/5 text-white focus:outline-none focus:border-red-700 focus:ring-1 focus:ring-red-700 transition" type="text" name="name" :value="old('name')" required autofocus />
            </div>

            {{-- Username --}}
            <div class="mt-4">
                <x-label for="username" value="{{ __('Username') }}" />
                <x-input id="username" class="block mt-1 w-full px-4 py-2 rounded-lg border border-white/30 bg-white/5 text-white focus:outline-none focus:border-red-700 focus:ring-1 focus:ring-red-700 transition" type="text" name="username" :value="old('username')" required />
            </div>

            {{-- Role --}}
            <div class="mt-4">
                <x-label for="role" value="{{ __('Register As') }}" />
                <select id="role" name="role" required class="block mt-1 w-full px-4 py-2 rounded-lg border border-white/30 bg-white/5 text-white focus:outline-none focus:border-red-700 focus:ring-1 focus:ring-red-700 transition">
                    <option value="" disabled selected hidden>-- Select Role --</option>
                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                    <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                </select>
            </div>

            {{-- Password --}}
            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full px-4 py-2 rounded-lg border border-white/30 bg-white/5 text-white focus:outline-none focus:border-red-700 focus:ring-1 focus:ring-red-700 transition" type="password" name="password" required />
            </div>

            {{-- Confirm Password --}}
            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full px-4 py-2 rounded-lg border border-white/30 bg-white/5 text-white focus:outline-none focus:border-red-700 focus:ring-1 focus:ring-red-700 transition" type="password" name="password_confirmation" required />
            </div>

            {{-- Submit Button --}}
            <div class="auth-actions flex justify-center items-center mt-4 w-full">
                <div class="flex-1">
             <a href="{{ route('login') }}" class="text-sm text-gray-200 hover:text-red-400">
                               Already have account? Login Now</a>
            </a>

    </div>
    <x-button type="submit" class="login-button items-center">
        {{ __('Register') }}
    </x-button>
</div>

        </form>
    </div>
</x-guest-layout>
