<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resultados de Admisión - CUP FICCT</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        .container { max-w-xl mx-auto padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px; background-color: #f8fafc; }
        .header { text-align: center; margin-bottom: 20px; }
        .content { background-color: #ffffff; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #64748b; }
        .status-admitido { color: #15803d; font-weight: bold; font-size: 18px; }
        .status-aprobado { color: #b45309; font-weight: bold; font-size: 18px; }
        .status-reprobado { color: #b91c1c; font-weight: bold; font-size: 18px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Resultados de Admisión - CUP FICCT</h2>
        </div>
        <div class="content">
            <p>Estimado/a <strong>{{ $usuario->nombre }} {{ $usuario->apellidopat }}</strong>,</p>
            
            <p>Le informamos que han concluido las evaluaciones del Curso Universitario de Preparación (CUP).</p>
            
            <p><strong>Su promedio final:</strong> {{ $postulacion->promedio }} / 100</p>
            
            <p>Estado de Admisión: 
                @if($postulacion->estado_admision === 'ADMITIDO')
                    <span class="status-admitido">¡Felicidades! Usted ha sido ADMITIDO/A.</span>
                    <br><br>Carrera asignada: <strong>{{ \App\Models\Carrera::where('codigo', $postulacion->carrera_admitida)->value('nombre') ?? $postulacion->carrera_admitida }}</strong>
                @elseif($postulacion->estado_admision === 'APROBADO_SIN_CUPO' || $postulacion->estado_admision === 'APROBADO_PENDIENTE')
                    <span class="status-aprobado">APROBADO.</span>
                    <br><br>Su nota es aprobatoria, pero lamentablemente los cupos para las carreras que seleccionó se han agotado.
                @else
                    <span class="status-reprobado">REPROBADO.</span>
                    <br><br>Lo sentimos, no alcanzó la nota mínima de aprobación.
                @endif
            </p>
            
            <p>Le agradecemos su participación en este proceso.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Facultad de Ingeniería en Ciencias de la Computación y Telecomunicaciones</p>
        </div>
    </div>
</body>
</html>
