@extends('layouts.layout')

@section('title', 'Inscription')

@section('content')
<div class="register-container">
    <h1 class="register-title">Inscription</h1>

    {{-- Message de session --}}
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Nom d'utilisateur --}}

        <div class="form-group">
            <label for="name" class="form-label">Nom d'utilisateur *</label>
            <input type="name" id="name" name="name" value="{{ old('name') }}"
                class="form-control @error('name') is-invalid @enderror" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Adresse e-mail --}}
        <div class="form-group">
            <label for="email" class="form-label">Adresse e-mail *</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror" required>
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

        {{-- Confirmer le mot de passe --}}
        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                class="form-control @error('password_confirmation') is-invalid @enderror" required>
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Liens --}}
        <div class="register-links">
            <a href="{{ route('login') }}" class="text-muted">
                Déjà un compte ? Connectez-vous
            </a>
        </div>

        {{-- Bouton --}}
        <div class="d-grid">
            <button type="submit" class="btn btn-custom">
                S'inscrire
            </button>
        </div>
    </form>
</div>
@endsection
