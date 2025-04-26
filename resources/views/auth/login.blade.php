@extends('layouts.layout')

@section('title', 'Connexion')

@section('content')
<div class="login-container">
    <h1 class="login-title">Connexion</h1>

    {{-- Message de session --}}
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Adresse e-mail --}}
        <div class="form-group">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Mot de passe --}}
        <div class="form-group">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" id="password" name="password"
                class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Rappel --}}
        <div class="form-group form-check">
            <input type="checkbox" name="remember" id="remember" class="form-check-input">
            <label for="remember" class="form-check-label">
                Se souvenir de moi
            </label>
        </div>

        {{-- Liens --}}
        <div class="login-links">
            <a href="{{ route('password.request') }}" class="text-muted">
                {{-- Si le mot de passe est oublié --}}
                Mot de passe oublié ?
            </a>
            <a href="{{ route('register') }}" class="text-muted">
                {{-- Si pas encore de compte --}}
                Pas encore de compte ? Inscrivez-vous
            </a> 
        </div>

        {{-- Bouton --}}
        <div class="d-grid">
            <button type="submit" class="btn btn-custom">
                Se connecter
            </button>
        </div>
    </form>
</div>
@endsection
