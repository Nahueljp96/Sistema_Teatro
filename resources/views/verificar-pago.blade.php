<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Pago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            max-width: 600px;
            background: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: bold;
        }
        .btn-custom {
            background-color: #0d6efd;
            color: white;
            border: none;
        }
        .btn-custom:hover {
            background-color: #0b5ed7;
        }
        .result-container {
            margin-top: 30px;
        }
        .alert-info {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Verificar Pago</h2>
        <form action="{{ route('verificar-pago.submit') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="mb-3">
                <label for="dni" class="form-label">Ingrese DNI:</label>
                <input type="text" name="dni" id="dni" class="form-control" placeholder="Ejemplo: 12345678" required>
                <div class="invalid-feedback">
                    Por favor, ingrese un DNI válido.
                </div>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-custom btn-lg">Verificar</button>
            </div>
        </form>

        @if(isset($alumno))
        <div class="result-container">
            <h4 class="text-success mt-4">Alumno encontrado</h4>
            <ul class="list-group">
                <li class="list-group-item"><strong>Nombre:</strong> {{ $alumno->nombre . ' ' . $alumno->apellido }}</li>
                <li class="list-group-item"><strong>Días restantes para vencimiento:</strong> {{ $diasRestantes }}</li>
            </ul>
        </div>
        @elseif($errors->any())
        <div class="alert alert-danger mt-4">
            {{ $errors->first() }}
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validación de Bootstrap
        (() => {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>
</html>
