<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
        <header style="background-color: #4CAF50; padding: 10px; color: #ffffff; border-radius: 8px 8px 0 0;">
            <h1 style="margin: 0; font-size: 24px;">Notificación del Sistema</h1>
        </header>
        <main style="padding: 20px; color: #333;">
            <p>Recibiste un correo automáticamente del sistema.</p>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px; font-weight: bold;">Nombre:</td>
                    <td style="padding: 8px;">{{ $mensaje['nombre'] }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold;">Correo:</td>
                    <td style="padding: 8px;">{{ $mensaje['email'] }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold;">Asunto:</td>
                    <td style="padding: 8px;">{{ $mensaje['asunto'] }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold;">Mensaje:</td>
                    <td style="padding: 8px;">{{ $mensaje['mensaje'] }}</td>
                </tr>
            </table>
        </main>
        <footer style="background-color: #f1f1f1; padding: 10px; text-align: center; color: #777; font-size: 12px; border-radius: 0 0 8px 8px;">
            <p>&copy; 2024 Tu Empresa. Todos los derechos reservados.</p>
        </footer>
    </div>
</body>
</html>