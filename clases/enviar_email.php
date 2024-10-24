<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/tienda_online/phpmailer/PHPMailer-6.9.1/src/PHPMailer.php';
require 'C:/xampp/htdocs/tienda_online/phpmailer/PHPMailer-6.9.1/src/SMTP.php';
require 'C:/xampp/htdocs/tienda_online/phpmailer/PHPMailer-6.9.1/src/Exception.php';

// Inicia la sesión
session_start();

// Asegúrate de que el usuario esté autenticado
if (!isset($_SESSION['user_email'])) {
    echo "Error: no se ha podido obtener el correo del usuario.";
    exit;
}

// Utiliza el correo del usuario
$email_usuario = $_SESSION['user_email'];

// Configuración de PHPMailer
$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'proyectoporgra@gmail.com';             // SMTP username
    $mail->Password   = 'yabo amsh xkcs lfar';                  // App-specific password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            // Enable implicit TLS encryption
    $mail->Port       = 465;                                    // TCP port to connect to (SSL)

    // Recipients
    $mail->setFrom('proyectoporgra@gmail.com', 'TechShop');
    $mail->addAddress($email_usuario);  // Usa el correo del usuario

    // Content
    $mail->isHTML(true);                                        // Set email format to HTML
    $mail->Subject = 'Detalles de su compra';

    $cuerpo = '<h4>Gracias por su compra</h4>';
    $cuerpo .= '<p>El ID de su compra es: <b>' . $id_transaccion . '</b></p>';

    $mail->Body    = $cuerpo;
    $mail->AltBody = 'Le enviamos los detalles de su compra';

    // Set language to Spanish
    $mail->setLanguage('es', 'C:/xampp/htdocs/tienda_online/phpmailer/language/phpmailer.lang-es.php');

    // Send email
    $mail->send();
    echo "Correo enviado correctamente";
} catch (Exception $e) {
    echo "Error al enviar el correo electronico de la compra: {$mail->ErrorInfo}";
    exit;
}

?>
