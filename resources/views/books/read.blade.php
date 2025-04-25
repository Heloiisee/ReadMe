@extends('layouts.layout')

@section('title', 'Lire : ' . $ebook->title)
@section('description', 'Lecture AJAX de ' . $ebook->title)

@section('content')
<div class="reader-container">
    <h1>{{ $ebook->title }}</h1>
    <h2 class="text-muted">par {{ $ebook->author }}</h2>
    <a href="{{ route('books.index') }}" class="btn btn-light my-3">← Retour à la bibliothèque</a>

    <div class="row">
        <div class="col-md-3">
            <h4>Chapitres</h4>
            <ul class="list-group" id="chapter-list">
                @foreach($chapters as $index => $chapter)
                    <li class="list-group-item">
                        <a href="#" class="chapter-link" data-index="{{ $index }}">{{ $chapter->label() }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="col-md-9">
            <div id="chapter-loader" style="display: none;">Chargement...</div>
            <div id="chapter-content">
                <p class="text-muted">Sélectionne un chapitre à gauche.</p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const links = document.querySelectorAll(".chapter-link");
        const content = document.getElementById("chapter-content");
        const loader = document.getElementById("chapter-loader");
        let currentIndex = null;
        const totalChapters = {{ count($chapters) }};

        function setActiveLink(index) {
            document.querySelectorAll(".chapter-link").forEach(link => {
                link.parentElement.classList.remove("active");
            });
            const active = document.querySelector(`.chapter-link[data-index="${index}"]`);
            if (active) {
                active.parentElement.classList.add("active");
            }
        }

        function loadChapter(index) {
            currentIndex = index;
            loader.style.display = "block";
            content.innerHTML = "";

            fetch(`/books/{{ $ebook->id }}/chapter/${index}`)
                .then(res => res.json())
                .then(data => {
                    loader.style.display = "none";
                    if (data.error) {
                        content.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                    } else {
                        content.innerHTML = `
                            <h3>${data.label}</h3>
                            <div>${data.content}</div>
                            <div class="d-flex justify-content-between mt-4">
                                <button id="prev-chapter" class="btn btn-outline-primary" ${index <= 0 ? 'disabled' : ''}>← Précédent</button>
                                <button id="next-chapter" class="btn btn-outline-primary" ${index >= totalChapters - 1 ? 'disabled' : ''}>Suivant →</button>
                            </div>
                        `;

                        // <-- FAIRE DEFILER VERS LE HAUT
                        window.scrollTo({ top: 0, behavior: 'smooth' });

                        setActiveLink(index);

                        document.getElementById("prev-chapter")?.addEventListener("click", () => {
                            if (currentIndex > 0) loadChapter(currentIndex - 1);
                        });

                        document.getElementById("next-chapter")?.addEventListener("click", () => {
                            if (currentIndex < totalChapters - 1) loadChapter(currentIndex + 1);
                        });
                    }
                }).catch(err => {
                    loader.style.display = "none";
                    content.innerHTML = `<div class="alert alert-danger">Erreur de chargement</div>`;
                });
        }

        links.forEach(link => {
            link.addEventListener("click", function (e) {
                e.preventDefault();
                const index = parseInt(this.dataset.index);
                loadChapter(index);
            });
        });
    });
</script>
@endsection
