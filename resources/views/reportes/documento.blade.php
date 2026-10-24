<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ $titulo }}</title>
    <style>
        /* DOMPDF no descarga la fuente Inter (Google Fonts); se usa DejaVu Sans,
           la sans-serif disponible en DomPDF más cercana a la tipografía del sistema. */
        * { font-family: "DejaVu Sans", Arial, sans-serif; }
        body { color: #1f2937; font-size: 11px; margin: 26px; }
        .encabezado { border-bottom: 3px solid #0B3C78; padding-bottom: 10px; margin-bottom: 14px; }
        .encabezado td { vertical-align: middle; }
        .logo { width: 54px; height: 54px; }
        .marca { color: #0B3C78; font-weight: bold; font-size: 16px; }
        .subtitulo { color: #7B1E2B; font-size: 11px; }
        h1 { font-size: 15px; margin: 12px 0 2px; color: #0B3C78; }
        .descripcion { color: #6b7280; font-size: 10px; margin-bottom: 8px; }
        .meta { font-size: 9px; color: #6b7280; margin-bottom: 12px; }
        .meta span { margin-right: 14px; }
        table.resumen { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        table.resumen td { border: 1px solid #e5e7eb; padding: 6px; text-align: center; background: #f8fafc; }
        table.resumen .valor { font-size: 14px; font-weight: bold; color: #0B3C78; }
        table.resumen .etiqueta { font-size: 8.5px; color: #6b7280; }
        table.datos { width: 100%; border-collapse: collapse; }
        table.datos th { background: #0B3C78; color: #ffffff; padding: 6px 5px; font-size: 9.5px; text-align: left; border: 1px solid #0B3C78; }
        table.datos td { padding: 5px; border: 1px solid #e5e7eb; font-size: 9.5px; }
        table.datos tr:nth-child(even) td { background: #f8fafc; }
        .pie { margin-top: 14px; font-size: 8.5px; color: #9ca3af; text-align: center; border-top: 1px solid #e5e7eb; padding-top: 6px; }
    </style>
</head>
<body>
    <div class="encabezado">
        @php
            $rutaLogo = public_path('images/logo-linea61.png');
            $logo = is_file($rutaLogo)
                ? 'data:image/png;base64,'.base64_encode(file_get_contents($rutaLogo))
                : null;
        @endphp
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                @if($logo)
                    <td width="64"><img src="{{ $logo }}" class="logo" alt="Línea 61"></td>
                @endif
                <td>
                    <div class="marca">Línea 61 — Sistema de Control y Seguimiento de Micros</div>
                    <div class="subtitulo">Santa Cruz de la Sierra · Bolivia</div>
                </td>
            </tr>
        </table>
    </div>

    <h1>{{ $titulo }}</h1>
    <div class="descripcion">{{ $descripcion }}</div>

    <div class="meta">
        @forelse($filtrosAplicados as $etiqueta => $valor)
            <span><strong>{{ $etiqueta }}:</strong> {{ $valor }}</span>
        @empty
            <span>Sin filtros aplicados</span>
        @endforelse
        <span><strong>Generado:</strong> {{ $generadoEn->format('d/m/Y H:i') }}</span>
        <span><strong>Total:</strong> {{ count($filas) }} registros</span>
    </div>

    @if(!empty($resumen))
        <table class="resumen">
            <tr>
                @foreach($resumen as $etiqueta => $valor)
                    <td>
                        <div class="valor">{{ $valor }}</div>
                        <div class="etiqueta">{{ $etiqueta }}</div>
                    </td>
                @endforeach
            </tr>
        </table>
    @endif

    <table class="datos">
        <thead>
            <tr>
                @foreach($encabezados as $encabezado)
                    <th>{{ $encabezado }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($filas as $fila)
                <tr>
                    @foreach($claves as $clave)
                        <td>{{ $fila[$clave] }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($encabezados) }}" style="text-align:center;color:#6b7280;">Sin registros para los filtros seleccionados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pie">Documento generado automáticamente por el sistema Línea 61.</div>
</body>
</html>
