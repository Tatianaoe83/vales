<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ticket #{{ $sale->folio }}</title>
    <style>
        @media print {
            body { margin: 0; padding: 0; }
            @page { margin: 0; }
            .no-print { display: none; }
        }
        
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            width: 80mm;
            margin: 0 auto;
            background: #fff;
            color: #000;
        }

        .ticket-container {
            padding: 5px;
            margin-bottom: 20px;
        }

        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        
        .divider { border-top: 1px dashed #000; margin: 5px 0; }
        .double-divider { border-top: 2px double #000; margin: 5px 0; }

        .items-table { width: 100%; font-size: 12px; }
        .items-table td { vertical-align: top; }
        
        .cut-line {
            border-top: 2px dashed #000;
            margin: 20px 0;
            position: relative;
        }
        .cut-icon {
            position: absolute;
            left: 50%;
            top: -10px;
            background: #fff;
            padding: 0 5px;
            font-size: 10px;
        }
    </style>
</head>
<body onload="window.print()">

    <div class="ticket-container">
        <div class="center">
            <h2 style="margin:0;">MATERIALES AGREGADOS</h2>
            <p style="margin:2px;">RFC: AAA010101AAA</p>
            <p style="margin:2px;">Mérida, Yucatán</p>
            <p class="bold">TICKET DE VENTA</p>
        </div>

        <div class="divider"></div>
        
        <div>
            Folio: <span class="bold">{{ $sale->folio }}</span><br>
            Fecha: {{ $sale->created_at->format('d/m/Y H:i') }}<br>
            Cliente: {{ substr($sale->client->name, 0, 20) }}...
        </div>

        <div class="divider"></div>

        <table class="items-table">
            <tr>
                <td colspan="3" class="bold">PRODUCTO</td>
            </tr>
            @foreach($sale->vales as $vale)
            <tr>
                <td>{{ $vale->cantidad }} {{ $vale->material->unidad_medida }}</td>
                <td>{{ $vale->material->nombre }}</td>
                <td class="right">${{ number_format($vale->material->precio_actual * $vale->cantidad, 2) }}</td>
            </tr>
            <tr>
                <td colspan="3" style="font-size: 10px;">>> Vale: {{ $vale->folio_vale }}</td>
            </tr>
            @endforeach
        </table>

        <div class="double-divider"></div>

        <table class="items-table">
            <tr>
                <td class="right bold">TOTAL:</td>
                <td class="right bold" style="font-size: 14px;">${{ number_format($sale->total, 2) }}</td>
            </tr>
            <tr>
                <td class="right">Condición:</td>
                <td class="right">{{ $sale->tipo_venta }}</td>
            </tr>
        </table>

        <br>
        <div class="center">
            </div>
    </div>
</body>
</html>