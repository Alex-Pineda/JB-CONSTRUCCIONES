<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Incluir las clases necesarias
require __DIR__ . '/../PHPMailer-master/src/Exception.php';
require __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer-master/src/SMTP.php';

// Datos del usuario receptor (correo al que se enviará el mensaje)
$correoDestino = "juanbustamante1128@gmail.com";
$nombreUsuario = "Usuario Juan Bustamante";

// Enlace de recuperación simulado
$token = bin2hex(random_bytes(16));
$enlace = "https://localhost/JB-CONSTRUCCIONES/Paginas/restablecerContrasena.php?token=$token";

$mail = new PHPMailer(true);
$mail->CharSet = 'UTF-8';

try {
    // Configuración del servidor SMTP (Gmail)
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'alexkrimen@gmail.com'; // Gmail del remitente
    $mail->Password = 'zkapjqncddxshatv'; // Contraseña de aplicación de Gmail
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
    $mail->Port = 587;

    // Soluciona posibles errores con certificados SSL en localhost
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ];

    // Remitente y destinatario
    $mail->setFrom('alexkrimen@gmail.com', 'Administración JB-CONSTRUCCIONES');
    $mail->addAddress($correoDestino, $nombreUsuario);

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Recuperación de cuenta';
    $mail->Body = "
        <div style='font-family: Arial, sans-serif; padding: 10px;'>
            <h2 style='color:#1e795a;'>Recuperación de cuenta</h2>
            <p>Hola <b>$nombreUsuario</b>, has solicitado recuperar tu cuenta.</p>
            <p>Para continuar, haz clic en el siguiente enlace:</p><br>
            <a href='$enlace' style='display:inline-block; padding:10px 20px; background-color:#1e795a; color:white; border-radius:6px; text-decoration:none;'>Restablecer contraseña</a><br><br>
            <p>Este enlace es temporal y expirará pronto.</p>
            <hr>
            <p>Atentamente,<br><b>Administración JB-CONSTRUCCIONES</b></p>
        </div>
    ";

    // Enviar el correo
    $mail->send();
    echo "✅ Correo de prueba enviado correctamente a $correoDestino.";

} catch (Exception $e) {
    echo "❌ Error al enviar el correo: {$mail->ErrorInfo}";
}
?>
