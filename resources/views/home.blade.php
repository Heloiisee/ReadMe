@extends('layouts.layout')

{{-- PAGE D'ACCUEIL --}}
{{-- Titre de la page d'accueil --}}
@section('title', 'Accueil')

{{-- PARTIE CONTENU DE L'ACCUEIL --}}
@section('content')

<div class="hero-container d-flex align-items-center justify-content-center">
    <div class="hero-content text-center">
        <img src="{{ asset('images/ebook.jpg') }}" alt="Illustration de lecture" class="hero-image img-fluid">
        <h1>Bienvenue sur <span class="brand">ReadMe</span></h1>
        <p class="hero-subtitle">Découvrez, lisez et emportez vos livres préférés partout.</p>
        <a href="{{ route('books.create') }}" class="btn btn-custom mt-3"><i class="fa fa-plus-circle me-2"></i>Ajouter un eBook</a>
        <a href="{{ route('books.index')}}" class="btn btn-outline-secondary mt-3"><i class="fa fa-book me-2"></i>Explorer la bibliothèque</a>
    </div>
</div>

<!-- Section Pourquoi Bookly -->
<section class="why-bookly container mt-5">
    <h2 class="text-center mb-4">Pourquoi choisir Bookly ?</h2>
    <div class="row">
        <div class="col-md-6 text-center">
            <i class="fa fa-book-open fa-3x mb-3"></i>
            <h4>Accès illimité</h4>
            <p>Accédez à vos livres préférés à tout moment.</p>
        </div>
        <div class="col-md-6 text-center">
            <i class="fa fa-cloud-upload-alt fa-3x mb-3"></i>
            <h4>Ajoutez vos livres</h4>
            <p>Importez vos propres eBooks en toute simplicité.</p>
        </div>
        
    </div>
</section>

@endsection