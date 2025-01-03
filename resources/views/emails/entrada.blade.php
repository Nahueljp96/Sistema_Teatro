<!DOCTYPE html>
<html>
<head>
    <title>Confirmación de Compra</title>
</head>
<body>
    <h1>¡Gracias por tu compra, {{ $entrada->nombre_comprador}}!</h1>
    <p>Tu entrada para la obra <strong>{{ $entrada->obra->titulo }}</strong> ha sido confirmada.</p>
    
    <p>¡Esperamos que disfrutes la función!</p>

    <p>Has comprado entradas para la obra <strong>{{ $entrada->obra->titulo }}</strong>.</p>
    <p>Cantidad de entradas: {{ $entrada->cantidad }}</p>
    <p>Codigo unico:: {{ $entrada->preference_id }}</p>
    <p>Nos vemos en el teatro. ¡Disfruta del espectáculo!</p>
</body>
</html>
