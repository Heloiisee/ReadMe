@extends('layouts.layout')

@section('title', 'Paramètres du compte')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Paramètres du compte ⚙️</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Profil --}}
    <div class="card mb-4">
        <div class="card-header">🧑 Modifier Profil</div>
        <div class="card-body">
            <form action="{{ route('settings.updateProfile') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <button class="btn btn-primary">💾 Sauvegarder</button>
            </form>
        </div>
    </div>

    {{-- Mot de passe --}}
    <div class="card mb-4">
        <div class="card-header">🔑 Changer le mot de passe</div>
        <div class="card-body">
            <form action="{{ route('settings.updatePassword') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Mot de passe actuel</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirmer le nouveau mot de passe</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button class="btn btn-warning">🔒 Changer</button>
            </form>
        </div>
    </div>

    <!-- {{-- Thème clair/sombre --}}
    <div class="card mb-4">
    <div class="card-header">🎨 Apparence</div>
    <div class="card-body">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="themeSwitch" {{ $user->theme === 'dark' ? 'checked' : '' }}>
            <label class="form-check-label" for="themeSwitch">Mode sombre</label>
        </div>
    </div>
</div> -->

    {{-- Suppression compte --}}
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">❌ Supprimer mon compte</div>
        <div class="card-body">
            <p class="text-danger">Cette action est définitive. Tous tes livres seront aussi supprimés !</p>
            <form id="delete-account-form" action="{{ route('settings.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Supprimer définitivement</button>
            </form>
        </div>
    </div>
</div>
<!-- 
<script>
document.getElementById('themeSwitch').addEventListener('change', function () {
    const newTheme = this.checked ? 'dark' : 'light';

    fetch('{{ route('settings.updateTheme') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ theme: newTheme })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // ✅ Modifier dynamiquement la classe du body
            document.body.classList.remove('theme-light', 'theme-dark');
            document.body.classList.add(`theme-${newTheme}`);
        }
    })
    .catch(() => {
        alert('Erreur lors du changement de thème');
    });
});
</script> -->



@endsection
