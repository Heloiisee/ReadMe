@extends('layouts.layout')

@section('title', 'Nouveau mot de passe')

@section('content')
<div class="reset-password-container">
    <h1 class="reset-password-title">Nouveau mot de passe</h1>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        {{-- Jeton de réinitialisation --}}
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- Adresse e-mail --}}
        <div class="form-group">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}"
                class="form-control @error('email') is-invalid @enderror" required autofocus autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Nouveau mot de passe --}}
        <div class="form-group">
            <label for="password" class="form-label">Nouveau mot de passe</label>
            <input type="password" id="password" name="password"
                class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Confirmer le mot de passe --}}
        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                class="form-control @error('password_confirmation') is-invalid @enderror" required autocomplete="new-password">
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Bouton --}}
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">
                Réinitialiser le mot de passe
            </button>
        </div>
    </form>
</div>
@endsection
