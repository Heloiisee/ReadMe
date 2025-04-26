@extends('layouts.layout')

@section('title', 'Détails de l\'eBook')
@section('description', 'Détails de l\'eBook : ' . $book->title)
@section('keywords', 'ebook, détails, livre numérique')

@section('content')


<div class="book-container">
    <h1 class="book-title">Détails de l'eBook</h1>
    <a href="{{ route('books.index') }}" class="back-link">Retour à la bibliothèque</a>
    <div class="book-header">
        @if($book->cover_path)
        <div class="book-cover">
        @if ($book->cover_path && Storage::disk('public')->exists($book->cover_path))
    <img src="{{ Storage::url($book->cover_path) }}" alt="Couverture du livre" class="book-cover-img">
        @elseif ($book->cover_base64)
            <img src="data:image/jpeg;base64,{{ $book->cover_base64 }}" alt="Couverture du livre" class="book-cover-img">
        @else
            <div>Pas de couverture disponible</div>
        @endif
        </div>
        @endif

        <div class="book-meta" style="{{ $book->cover_path ? 'width: 66.666667%' : 'width: 100%' }}">
            <h1 class="book-title">{{ $book->title ?? 'Titre inconnu' }}</h1>
            <h2 class="book-author">par {{ $book->author ?? 'Auteur inconnu' }}</h2>

                <div style="max-width: 100%;">
                @if($book->description)
                    <p style="color: #4a5568; margin-bottom: 1.5rem;">{{ strip_tags($book->description,'<br><strong>') }}</p>
                @endif
            </div>



                <div class="book-details">
                    <div><span>Format :</span> {{ pathinfo($book->file_path, PATHINFO_EXTENSION) }}</div>
                    <div><span>Taille :</span> {{ number_format(Storage::disk('public')->size($book->file_path) / 1024 / 1024, 2) }} MB</div>
                </div>
                <a href="{{ route('books.read', $book->id) }}" class="btn btn-read mt-4">
                    <i class="fas fa-book-open me-2"></i> Lire le livre
                </a>
            </div>
        </div>
    </div>

@endsection
