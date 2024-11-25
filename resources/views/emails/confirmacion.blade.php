<!DOCTYPE html>
<html>
<head>
    <title>Confirmación de Compra</title>
</head>
<body>
    <h1>¡Gracias por tu compra, {{ $entrada->usuario->name }}!</h1>
    <p>Tu entrada para la obra <strong>{{ $entrada->obra->titulo }}</strong> ha sido confirmada.</p>
    <p>Adjuntamos tu entrada en formato PDF.</p>
    <p>¡Esperamos que disfrutes la función!</p>
</body>
</html>
