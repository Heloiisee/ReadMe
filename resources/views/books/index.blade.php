@extends('layouts.layout')

@section('title', 'Ma bibliothèque')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="library-container">
    <h1>📚 Ma Bibliothèque</h1>
        <div class="mb-4">
        <a href="{{ route('books.create') }}" class="btn btn-custom">
            <i class="fas fa-plus"></i> Ajouter un livre
        </a>
    </div>

    {{-- Message de session --}}


    @if ($books->isNotEmpty())
        <div class="book-grid">
            @foreach ($books as $book)
                <div class="book-card">
                    <div class="book-cover">
                        @if ($book->cover_path && Storage::disk('public')->exists($book->cover_path))
                            <img src="{{ Storage::url($book->cover_path) }}" alt="Couverture du livre">
                        @else
                            <div class="no-cover">Pas de couverture</div>
                        @endif
                    </div>

                    <div class="book-info">
                        <h3>{{ $book->title }}</h3>
                        <p>📖 Auteur : <strong>{{ $book->author ?? 'Inconnu' }}</strong></p>
                        <p class="book-description">{{ Str::limit($book->description, 100, '...') }}</p>
                    </div>

                    <div class="book-actions">
                        <a href="{{ route('books.show', $book->id) }}" class="btn btn-view">
                            <i class="fas fa-book-open"></i> Voir
                        </a>
                        <form action="{{ route('books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Supprimer ce livre ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete">
                                <i class="fas fa-trash-alt"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="no-books">Aucun livre disponible pour le moment.</p>
    @endif
</div>
@endsection
