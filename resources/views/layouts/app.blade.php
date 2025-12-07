<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Alimni') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Styles -->
        @livewireStyles

        <!-- PWA  -->
    <meta name="theme-color" content="#6777ef"/>
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">

    </head>

    <body class="pl-64">
        <x-banner />
<!--
        <button id="pwa-install-btn" style="display:none; position: fixed; bottom: 20px; right: 20px; padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 8px; z-index: 1000;">
        Install App
        </button>

        <script>
        let deferredPrompt;
        const btnAdd = document.getElementById('pwa-install-btn');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            btnAdd.style.display = 'block';
        });

        btnAdd.addEventListener('click', () => {
            if (!deferredPrompt) return;

            deferredPrompt.prompt();

            deferredPrompt.userChoice.then(() => {
                deferredPrompt = null;
                btnAdd.style.display = 'none';
            });
        });
        </script> -->


        <div class="content">
            @livewire('navigation-menu')

            <!-- Page Heading -->
           @if(View::hasSection('header'))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        @yield('header')
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>

        @stack('modals')

        @livewireScripts

        <script src="{{ asset('pwa-install.js') }}"></script>
        <script src="{{ asset('/sw.js') }}"></script>
        <script>
        if ("serviceWorker" in navigator) {
            navigator.serviceWorker.register("/sw.js")
                .then((reg) => console.log("Service Worker registered:", reg))
                .catch((err) => console.error("SW registration failed:", err));
        }
        </script>

    </body>
</html>

