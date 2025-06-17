<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Aukcje')</title>
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">System Aukcyjny</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Logowanie</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Rejestracja</a>
                        </li>
                    @else
                        {{-- Dodaj aukcję --}}
                        <li class="nav-item me-lg-3">
                            <a class="btn btn-sm btn-primary" href="{{ route('auctions.create') }}">Dodaj aukcję</a>
                        </li>
                        {{-- Profil użytkownika --}}
                        <li class="nav-item">
                            <a class="btn btn-link nav-link" href="{{ route('profile.edit') }}"> {{ Auth::user()->name }}</a>
                        </li>
                        {{-- Wylogowanie --}}
                        <li class="nav-item ms-3">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link">Wyloguj</button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Główna zawartość -->
    <main class="container py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-top mt-5">
        <div class="container text-center py-3 text-muted">
            © {{ date('Y') }} System Aukcyjny
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
