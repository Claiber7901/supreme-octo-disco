<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    function enviarEmail($email, $asunto, $cuerpo)
    {
        require_once __DIR__ . '/../config/config.php';
        require_once __DIR__ . '/../phpmailer/PHPMailer-6.9.1/src/PHPMailer.php';
        require_once __DIR__ . '/../phpmailer/PHPMailer-6.9.1/src/SMTP.php';
        require_once __DIR__ . '/../phpmailer/PHPMailer-6.9.1/src/Exception.php';
        
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug = 0;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'proyectoporgra@gmail.com';             // SMTP username
            $mail->Password   = 'yabo amsh xkcs lfar';                 // App-specific password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;           // Enable implicit TLS encryption
            $mail->Port       = 465;                                    // TCP port to connect to (SSL)
            
            // Correo emisor y nombre
            $mail->setFrom(MAIL_USER, 'CDP');

            // Correo receptor y nombre
            $mail->addAddress($email);

            // Contenido
            $mail->isHTML(true); // Establecer el formato de correo electrónico en HTML
            $mail->Subject = $asunto; // Título del correo

            // Cuerpo del correo
            $mail->Body = mb_convert_encoding($cuerpo,'ISO-8859-1','UTF-8');
            $mail->setLanguage('es', '../phpmailer/language/phpmailer.lang-es.php');

            // Enviar correo
            if ($mail->send()) {
                return true;
            } else {
                return false;
            }

        } catch (Exception $e) {
            echo "No se pudo enviar el mensaje. Error de envío: {$mail->ErrorInfo}";
            return false;
        }
    }
}
