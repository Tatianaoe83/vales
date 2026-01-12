<!DOCTYPE html>
<html>
<head>
    <title>Remisión de Entrega</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">

    <h2>Hola, {{ $sale->client->name }}</h2>

    <p>Adjunto la remisión y los vales correspondientes a tu compra reciente.</p>

    <ul>
        <li><strong>Folio:</strong> {{ $sale->folio }}</li>
        <li><strong>Fecha:</strong> {{ $sale->created_at->format('d/m/Y') }}</li>
        <li><strong>Total:</strong> ${{ number_format($sale->total, 2) }}</li>
    </ul>

    <p>Gracias por tu preferencia.</p>
    
    <p>Atentamente,<br>
    <strong>Materiasles Agregados
    </strong></p>

</body>
</html>