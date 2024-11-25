<!DOCTYPE html>
<html>
<head>
    <title>Entrada</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Obra: {{ $entrada->obra->titulo }}</h1>
        <img src="{{ public_path('images/logo.png') }}" alt="Logo" width="150">
    </div>
    <p><strong>Nombre:</strong> {{ $entrada->usuario->name }}</p>
    <p><strong>Email:</strong> {{ $entrada->email }}</p>
    <p><strong>Asiento:</strong> {{ $entrada->asiento }}</p>
    <p><strong>Fecha:</strong> {{ $entrada->obra->fecha }}</p>
</body>
</html>
