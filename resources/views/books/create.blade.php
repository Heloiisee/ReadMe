@extends('layouts.layout')
    
    {{-- PARTIE TITRE ET DESCRIPTION--}}
@section('title', 'Ajouter un eBook')
@section('description', 'Ajoutez un nouvel eBook à votre bibliothèque.')

{{-- PARTIE CONTENU --}}

@section('content')

<main class="upload-container">
    <section class="upload-card">
        <h2>Ajouter un ebook</h2>
        <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
            <label for="file" class="form-label">Fichier eBook</label>
            <input type="file" name="file" id="file" accept=".epub,.pdf,.fb2,.azw3" class="form-input" required>
            <p class="form-hint">Formats supportés: EPUB, PDF, FB2, AZW3</p>
        </div>
        
        <button type="submit" class="btn-submit">Ajouter</button>
        <a href="{{ url('/') }}" class="back-link">Retour à l'accueil</a>
        </form>

    </section>

</main>
@endsection