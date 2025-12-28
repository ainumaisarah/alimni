<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>'Alimni | Welcome</title>

    {{-- Load Tailwind + app.css --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="auth-page">

    {{-- Navbar --}}
    <nav>
        <div class="nav-container">
            <div class="logo-section">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
                <span>'Alimni</span>
            </div>

            <div class="nav-links">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}">Log in</a>

                        <!--@if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif-->
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="hero">
        <h1>Welcome to 'Alimni !</h1>
        <p class = "font-semibold">Discover an interactive learning experience to inspire excellence.
            Learn anywhere, anytime — your education, your way.</p>
    </section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    if ("serviceWorker" in navigator) {
        console.log("SW: Supported");

        navigator.serviceWorker.register("/sw.js")
            .then(reg => console.log("SW registered:", reg.scope))
            .catch(err => console.error("SW registration failed:", err));
    } else {
        console.log("SW: NOT supported");
    }
});
</script>

</body>
</html>
