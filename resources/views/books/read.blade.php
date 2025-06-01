@extends('layouts.layout')

@section('title', 'Lire : ' . $ebook->title)
@section('description', 'Lecture AJAX de ' . $ebook->title)

@section('content')

<div class="reader-container">
    <h1>{{ $ebook->title }}</h1>
    <h2 class="text-muted">par {{ $ebook->author }}</h2>
    <a href="{{ route('books.index') }}" class="btn btn-light my-3">← Retour à la bibliothèque</a>


    @if ($fileType === 'pdf')
        {{-- LECTEUR PDF --}}
        <div class="pdf-container" style="position: relative; padding-top: 56.25%; height: 0; overflow: hidden;">
            <iframe src="{{ Storage::url($ebook->file_path) }}" 
                style="position: absolute; top:0; left:0; width:100%; height:100%; border: none;" 
                allowfullscreen>
            </iframe>
        </div>

    @else
        {{-- VERSION MOBILE --}}
        <div class="d-md-none mb-3">
            <button class="btn btn-outline-secondary w-100" type="button" data-bs-toggle="collapse" data-bs-target="#mobile-toc">
                📚 Voir les chapitres
            </button>
            <div class="collapse mt-2" id="mobile-toc">
                <ul class="list-group">
                    @foreach($chapters as $index => $chapter)
                        <li class="list-group-item">
                            <a href="#" class="chapter-link" data-index="{{ $index }}">{{ $chapter->label() }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="row">
            {{-- VERSION DESKTOP --}}
            <div class="col-md-3 d-none d-md-block">
                <h4>Chapitres</h4>
                <ul class="list-group" id="chapter-list">
                    @foreach($chapters as $index => $chapter)
                        <li class="list-group-item">
                            <a href="#" class="chapter-link" data-index="{{ $index }}">{{ $chapter->label() }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- CONTENU --}}
            <div class="col-md-9">
                <div id="chapter-loader" style="display: none;">Chargement...</div>

                <div id="chapter-progress" class="mb-3" style="display: none;">
                    <div class="d-flex justify-content-between text-muted small mb-1">
                        <span id="progress-text">Chapitre 1 sur X</span>
                        <span id="progress-percent">0%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-warning" id="progress-bar" role="progressbar" style="width: 0%;" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

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
            const progressContainer = document.getElementById("chapter-progress");
            const progressText = document.getElementById("progress-text");
            const progressPercent = document.getElementById("progress-percent");
            const progressBar = document.getElementById("progress-bar");

            const totalChapters = {{ count($chapters) }};
            const storageKey = 'lastChapter-{{ $ebook->id }}';
            let currentIndex = null;
            let resumeNotice = null;

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

                if (resumeNotice) {
                    resumeNotice.remove();
                    resumeNotice = null;
                }

                localStorage.setItem(storageKey, index);
                loader.style.display = "block";
                content.innerHTML = "";
                progressContainer.style.display = "none";

                fetch(`/books/{{ $ebook->id }}/chapter/${index}`)
                    .then(res => res.json())
                    .then(data => {
                        loader.style.display = "none";
                        if (data.error) {
                            content.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                        } else {
                            const current = index + 1;
                            const percent = Math.round((current / totalChapters) * 100);
                            progressText.innerText = `Chapitre ${current} sur ${totalChapters}`;
                            progressPercent.innerText = `${percent}%`;
                            progressBar.style.width = `${percent}%`;
                            progressBar.setAttribute("aria-valuenow", percent);
                            progressContainer.style.display = "block";

                            content.innerHTML = `
                                <h3>${data.label}</h3>
                                <div>${data.content}</div>
                                <div class="d-flex justify-content-between mt-4">
                                    <button id="prev-chapter" class="btn btn-outline-primary" ${index <= 0 ? 'disabled' : ''}>← Précédent</button>
                                    <button id="next-chapter" class="btn btn-outline-primary" ${index >= totalChapters - 1 ? 'disabled' : ''}>Suivant →</button>
                                </div>
                            `;

                            window.scrollTo({ top: 0, behavior: 'smooth' });
                            setActiveLink(index);

                            document.getElementById("prev-chapter")?.addEventListener("click", () => {
                                if (currentIndex > 0) loadChapter(currentIndex - 1);
                            });

                            document.getElementById("next-chapter")?.addEventListener("click", () => {
                                if (currentIndex < totalChapters - 1) loadChapter(currentIndex + 1);
                            });
                        }
                    }).catch(() => {
                        loader.style.display = "none";
                        content.innerHTML = `<div class="alert alert-danger">Erreur de chargement</div>`;
                    });
            }

            // Reprise de lecture (si chapitre sauvegardé)
            const saved = localStorage.getItem(storageKey);
            if (saved !== null && !isNaN(saved)) {
                const index = parseInt(saved);
                resumeNotice = document.createElement("div");
                resumeNotice.className = "alert alert-info";
                resumeNotice.textContent = `📌 Reprise au chapitre ${index + 1}`;
                content.parentElement.insertBefore(resumeNotice, content);

                // Attend un court instant avant de charger pour que le message s'affiche
                setTimeout(() => loadChapter(index), 2000);
            }

            // Écoute des clics sur les chapitres
            links.forEach(link => {
                link.addEventListener("click", function (e) {
                    e.preventDefault();
                    const index = parseInt(this.dataset.index);
                    loadChapter(index);

                    const isMobileToc = this.closest('#mobile-toc');
                    if (isMobileToc) {
                        const bsCollapse = bootstrap.Collapse.getInstance(isMobileToc) || new bootstrap.Collapse(isMobileToc, { toggle: false });
                        bsCollapse.hide();
                    }
                });
            });
        });
    </script>
    @endif
</div>
@endsection
