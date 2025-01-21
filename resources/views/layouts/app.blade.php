<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Teatro')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
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
            text-align: center;
            position: relative;
        }
        .hero::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
        }
        .hero h1 {
            font-size: 4rem;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
            z-index: 1;
        }
        .card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s;
        }
        .card:hover {
            transform: scale(1.05);
        }
        footer {
            background-color: #343a40;
            color: white;
        }
    /* WSP BOTON */    
    </style> 
        <a href="https://wa.me/1234567890" class="btn-wsp" target="_blank">
            <i class="fab fa-whatsapp"></i>
        </a>
        
        <style>
            .btn-wsp {
                position: fixed;
                bottom: 20px;
                right: 20px;
                background-color: #25d366;
                color: white;
                font-size: 2rem;
                width: 60px;
                height: 60px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
                z-index: 1000;
                transition: transform 0.3s, box-shadow 0.3s;
                text-decoration: none;
            }
        
            .btn-wsp:hover {
                transform: scale(1.1);
                box-shadow: 0px 6px 8px rgba(0, 0, 0, 0.2);
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
    <footer class="text-center py-3">
        <p>&copy; {{ date('Y') }} Teatro. Todos los derechos reservados.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>