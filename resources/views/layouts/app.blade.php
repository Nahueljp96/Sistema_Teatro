<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Teatro')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        .hero {
            background-image: url('@yield('hero-bg', 'https://via.placeholder.com/1920x600?text=Teatro')');
            background-size: cover;
            background-position: center;
            height: 60vh;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .hero h1 {
            font-size: 4rem;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
        }
    </style>
    @stack('styles')
</head>
<body>
    @include('partials.navbar')
    <div class="hero">
        <h1>@yield('hero-title', 'Bienvenidos al Mundo del Teatro')</h1>
    </div>
    <main class="container my-5">
        @yield('content')
    </main>
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; {{ date('Y') }} Teatro. Todos los derechos reservados.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
