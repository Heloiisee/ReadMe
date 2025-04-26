<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}"><i class="fa fa-book"></i> Bookly</a>

        <!-- Bouton responsive -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="fa fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
            @auth
                <li class="nav-item">
                    <a class="nav-link me-3" href="{{ url('/') }}"><i class="fa fa-home"></i> Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-3" href="{{ route('books.index') }}"><i class="fa fa-book"></i> Ma bibliothèque</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-3" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out-alt"></i> Déconnexion
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}"><i class="fa fa-home"></i> Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-3" href="{{ route('login') }}"><i class="fa fa-sign-in-alt"></i> Connexion</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-3" href="{{ route('register') }}"><i class="fa fa-user-plus"></i> Inscription</a>
                </li>
            @endauth

            </ul>
        </div>
    </div>
</nav>
