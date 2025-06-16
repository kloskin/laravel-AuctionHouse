{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="card shadow-lg" style="max-width: 24rem; width: 100%;">
        <div class="card-body p-4">
            <h2 class="card-title text-center mb-4">Logowanie</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Adres e-mail</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="form-control @error('email') is-invalid @enderror"
                    >
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label">Hasło</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        class="form-control @error('password') is-invalid @enderror"
                    >
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="form-check mb-4">
                    <input
                        type="checkbox"
                        name="remember"
                        id="remember"
                        class="form-check-input"
                        {{ old('remember') ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="remember">
                        Zapamiętaj mnie
                    </label>
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">
                        Zaloguj się
                    </button>
                </div>
            </form>

            <p class="text-center mb-2">
                Nie masz konta?
                <a href="{{ route('register') }}" class="link-primary">Zarejestruj się</a>
            </p>

            @if (Route::has('password.request'))
                <p class="text-center">
                    <a href="{{ route('password.request') }}" class="link-secondary small">
                        Zapomniałeś hasła?
                    </a>
                </p>
            @endif
        </div>
    </div>
</div>
@endsection
