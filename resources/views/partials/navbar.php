<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/">Teatro</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link @if(request()->is('/')) active @endif" href="/">Inicio</a></li>
                <li class="nav-item"><a class="nav-link @if(request()->is('nosotros')) active @endif" href="/nosotros">Nosotros</a></li>
                <li class="nav-item"><a class="nav-link @if(request()->is('cursos')) active @endif" href="/cursos">Cursos</a></li>
                <li class="nav-item"><a class="nav-link @if(request()->is('obras')) active @endif" href="/obras">Obras</a></li>
            </ul>
        </div>
    </div>
</nav>
