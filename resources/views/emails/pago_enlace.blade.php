<!DOCTYPE html>
<html>
<head>
    <title>Enlace de Pago</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-w: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="color: #4f46e5;">Hola, {{ $nombre }}</h2>
        <p style="color: #333333; line-height: 1.6;">
            Tus documentos han sido verificados y aprobados. Para finalizar tu inscripción al curso preuniversitario, debes realizar el pago correspondiente.
        </p>
        <p style="color: #333333; line-height: 1.6;">
            Haz clic en el siguiente enlace para acceder a la pasarela de pagos segura:
        </p>
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $enlace }}" style="background-color: #4f46e5; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">
                Realizar Pago
            </a>
        </div>
        <p style="color: #777777; font-size: 14px; text-align: center;">
            Este enlace es único y expirará en 48 horas.
        </p>
    </div>
</body>
</html>
