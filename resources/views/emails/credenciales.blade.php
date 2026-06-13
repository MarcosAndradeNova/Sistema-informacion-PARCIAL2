<!DOCTYPE html>
<html>
<head>
    <title>Inscripción Exitosa - Credenciales</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-w: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="color: #10b981; text-align: center;">¡Inscripción Exitosa!</h2>
        <p style="color: #333333; line-height: 1.6;">
            Tu pago ha sido procesado correctamente. Ahora eres un Postulante Activo en el sistema.
        </p>
        <p style="color: #333333; line-height: 1.6;">
            A continuación, te enviamos tus credenciales de acceso para que puedas iniciar sesión y consultar tu estado y grupo asignado:
        </p>
        
        <div style="background-color: #f3f4f6; padding: 20px; border-radius: 6px; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>Correo Electrónico (Usuario):</strong> {{ $emailUsuario }}</p>
            <p style="margin: 5px 0;"><strong>Contraseña:</strong> {{ $password }}</p>
        </div>

        <p style="color: #777777; font-size: 14px; text-align: center; margin-top: 30px;">
            Te recomendamos cambiar tu contraseña una vez que inicies sesión en el sistema.
        </p>
    </div>
</body>
</html>
