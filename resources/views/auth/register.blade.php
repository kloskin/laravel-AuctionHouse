@extends('layouts.app')

@section('content')
<div class="container vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="card shadow-sm w-100" style="max-width: 420px;">
        <div class="card-body p-4">
            <h2 class="card-title text-center mb-4">Rejestracja</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Name --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Nazwa użytkownika</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Adres e-mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label">Hasło</label>
                    <input id="password" type="password" name="password" required
                        class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Potwierdź hasło</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="form-control">
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">Zarejestruj się</button>
                </div>
            </form>

            <p class="text-center mb-0">
                Masz już konto?
                <a href="{{ route('login') }}" class="text-decoration-none">Zaloguj się</a>
            </p>
        </div>
    </div>
</div>
@endsection
