<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}"><i class="fa fa-book"></i> ReadMe</a>

        <!-- Bouton responsive -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="fa fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link me-3" href="{{ url('/') }}"><i class="fa fa-home"></i> Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-3" href="{{ route('books.create') }}"><i class="fa fa-plus-circle"></i> Ajouter un eBook</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-3" href="{{route('books.index')}}"><i class="fa fa-book"></i> Ma bibliothèque</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
