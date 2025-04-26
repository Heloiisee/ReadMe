@extends('layouts.layout')

@section('title', 'Mot de passe oublié')

@section('content')
<div class="password-reset-container">
    <h1 class="password-reset-title">Réinitialiser le mot de passe</h1>

    <div class="text-muted small mb-4 text-center">
        Vous avez oublié votre mot de passe ? Entrez votre adresse e-mail, nous vous enverrons un lien pour le réinitialiser.
    </div>

    {{-- Message de session --}}
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
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

        {{-- Bouton --}}
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">
                Envoyer le lien
            </button>
        </div>
    </form>
</div>
@endsection
