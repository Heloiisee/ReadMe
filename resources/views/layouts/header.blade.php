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
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                    {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('settings.edit') }}">⚙️ Paramètres</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item" type="submit">🚪 Déconnexion</button>
                        </form>
                    </li>
                </ul>
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
