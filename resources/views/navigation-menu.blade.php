<nav x-data="{ open: false }" class="sidebar-nav">

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto pl-4 pr-8 sm:pl-6 sm:pr-10 lg:pl-8 lg:pr-12">

        <div class="flex justify-between h-16">
            <div class="flex">

                <!-- Navigation Links -->
<div class="mt-6 flex flex-col space-y-3 px-6">
            <!-- Logo -->
                <div class="platform-logo">
                    <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo">
                    </a>
                </div>
    @auth
        @php
            $user = Auth::user();
        @endphp

        {{-- Admin Links --}}
        @if($user->hasRole('admin'))
            <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                {{ __('Dashboard') }}
            </x-nav-link>
            <x-nav-link href="{{ route('admin.classrooms.index') }}" :active="request()->routeIs('classes.index')">
                {{ __('Classes') }}
            </x-nav-link>
            <x-nav-link href="{{ route('admin.schedules.index') }}" :active="request()->routeIs('admin.home')">
                {{ __('Schedule') }}
            </x-nav-link>

        {{-- Teacher Links --}}
        @elseif($user->hasRole('teacher'))
            <x-nav-link href="{{ route('teacher.home') }}" :active="request()->routeIs('teacher.home')">
                {{ __('Home') }}
            </x-nav-link>
            <x-nav-link href="{{ route('teacher.dashboard') }}" :active="request()->routeIs('teacher.dashboard')">
                {{ __('Dashboard') }}
            </x-nav-link>
            <!-- Classes -->
                <x-nav-link href="{{ route('classes.index') }}" :active="request()->routeIs('classes.index')">
                    {{ __('Classes') }}
                </x-nav-link>
            <x-nav-link href="{{ route('chat.list') }}" :active="request()->routeIs('chat.*')">
                {{ __('Chat') }}
            </x-nav-link>

        {{-- Student Links --}}
        @elseif($user->hasRole('student'))
            <x-nav-link href="{{ route('student.home') }}" :active="request()->routeIs('student.home')">
                {{ __('Home') }}
            </x-nav-link>
            <x-nav-link href="{{ route('student.dashboard') }}" :active="request()->routeIs('student.dashboard')">
                {{ __('Dashboard') }}
            </x-nav-link>
            <!-- Classes -->
                <x-nav-link href="{{ route('classes.index') }}" :active="request()->routeIs('classes.index')">
                    {{ __('Classes') }}
                </x-nav-link>
            <x-nav-link href="{{ route('chat.list') }}" :active="request()->routeIs('chat.*')">
                {{ __('Chat') }}
            </x-nav-link>
        @endif
    @endauth
</div>
            </div>
</nav>

    <!-- MAIN CONTENT + TOP NAV -->
    <div class="flex-1 flex flex-col">

        <!-- TOP NAV / PROFILE -->
        <header class="fixed top-0 right-0 left-64 h-16 bg-white flex justify-end items-center px-6 z-50">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                    <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">

                    <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown -->
                <div x-show="open" @click.outside="open = false"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="absolute right-0 top-full -translate-y-1 w-48 bg-white border rounded-md shadow-lg z-50">

                    <span class="block px-4 py-2 text-left text-gray-700 font-medium">{{ Auth::user()->name }}</span>

                    <x-dropdown-link href="{{ route('profile.show') }}">Profile</x-dropdown-link>

                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                        <x-dropdown-link href="{{ route('api-tokens.index') }}">API Tokens</x-dropdown-link>
                    @endif

                    <div class="border-t border-gray-200"></div>

                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">Log Out</x-dropdown-link>
                    </form>
                </div>
            </div>
        </header>


    </div>
</div>
