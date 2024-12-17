<!DOCTYPE html>
<html>
<head>
    <title>Confirmación de Compra</title>
</head>
<body>
    <h1>¡Gracias por tu compra, {{ $cursoVenta->nombre_comprador }}!</h1>
    <p>Tu inscripción al curso <strong>{{ $cursoVenta->curso->nombre }}</strong> ha sido confirmada.</p>
    <p>Estado del pago: <strong>{{ $cursoVenta->estado_pago }}</strong></p>
    <p>Cantidad de inscripciones: {{ $cursoVenta->cantidad }}</p>
    <p>Adjuntamos un comprobante en formato PDF con los detalles de tu compra.</p>
    <p>Si tienes alguna consulta, no dudes en contactarnos.</p>

    <hr>

    <h1>¡Detalles de tu compra!</h1>
    <p>Hola,</p>
    <p>Has adquirido el curso <strong>{{ $cursoVenta->curso->nombre }}</strong>.</p>
    <p>Cantidad de inscripciones: {{ $cursoVenta->cantidad }}</p>
    <p>Nos vemos en el curso. ¡Gracias por elegirnos!</p>
</body>
</html>
