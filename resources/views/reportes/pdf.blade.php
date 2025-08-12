<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Movimientos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #082140;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #082140;
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .filtros {
            background: #f8f9fa;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .filtros h3 {
            margin: 0 0 10px 0;
            color: #3177bf;
            font-size: 14px;
        }
        .filtros p {
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #3177bf;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-entrada {
            background-color: #28a745;
            color: white;
        }
        .badge-salida {
            background-color: #dc3545;
            color: white;
        }
        .badge-certificado {
            background-color: #17a2b8;
            color: white;
        }
        .badge-descarte {
            background-color: #ffc107;
            color: black;
        }
        .totales {
            margin-top: 20px;
            padding: 15px;
            background: #e9ecef;
            border-radius: 5px;
        }
        .totales h3 {
            margin: 0 0 10px 0;
            color: #3177bf;
            font-size: 14px;
        }
        .totales-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
        .total-item {
            text-align: center;
            padding: 10px;
            background: white;
            border-radius: 3px;
        }
        .total-item strong {
            display: block;
            font-size: 16px;
            color: #3177bf;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE MOVIMIENTOS DE INVENTARIO</h1>
        <p>Generado el: {{ $fechaReporte }}</p>
    </div>

    @if($desde || $hasta)
    <div class="filtros">
        <h3>Filtros Aplicados:</h3>
        @if($desde)
            <p><strong>Desde:</strong> {{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }}</p>
        @endif
        @if($hasta)
            <p><strong>Hasta:</strong> {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }}</p>
        @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Tipo</th>
                <th>Responsable</th>
                <th>Evento/Destino</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movimientos as $mov)
            <tr>
                <td>{{ \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y') }}</td>
                <td>{{ $mov->producto->nombre ?? '-' }}</td>
                <td>{{ $mov->cantidad }}</td>
                <td>
                    @if($mov->tipo_movimiento == 'Entrada')
                        <span class="badge badge-entrada">Entrada</span>
                    @elseif($mov->tipo_movimiento == 'Salida')
                        <span class="badge badge-salida">Salida</span>
                    @elseif($mov->tipo_movimiento == 'Certificado')
                        <span class="badge badge-certificado">Certificado</span>
                    @else
                        <span class="badge badge-descarte">Descarte</span>
                    @endif
                </td>
                <td>{{ $mov->responsable ?? '-' }}</td>
                <td>{{ $mov->evento ?? '-' }}</td>
                <td>{{ $mov->observaciones ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; color: #666;">No hay movimientos para los filtros seleccionados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($movimientos->count() > 0)
    <div class="totales">
        <h3>Resumen de Totales</h3>
        <div class="totales-grid">
            <div class="total-item">
                <strong>{{ $totalEntradas }}</strong>
                <span>Entradas</span>
            </div>
            <div class="total-item">
                <strong>{{ $totalSalidas }}</strong>
                <span>Salidas</span>
            </div>
            <div class="total-item">
                <strong>{{ $totalDescartes }}</strong>
                <span>Descartes</span>
            </div>
            <div class="total-item">
                <strong>{{ $totalCertificados }}</strong>
                <span>Certificados</span>
            </div>
        </div>
    </div>
    @endif

    <div class="footer">
        <p>Reporte generado automáticamente por el Sistema de Administrativos</p>
        <p>Total de registros: {{ $movimientos->count() }}</p>
    </div>
</body>
</html> 