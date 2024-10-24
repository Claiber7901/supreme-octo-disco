<?php
define('SITE_URL', 'http://localhost/tienda_online/');

// Otras constantes...
define('CLIENT_ID', 'ARw78kQktKyUoVYCVj9-vqYoDNWy289g7EJVMxBOZ5SPMOv2jbbglstaz4Dy6KWOYqtzNNAsoQyY286O');
define('CURRENCY', 'USD');
define('KEY_TOKEN', 'APR.wqc-354*');
define('MONEDA', '$');
define('MAIL_USER', 'tu_correo@gmail.com');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inicialización del carrito
$num_cart = 0;
if (isset($_SESSION['carrito']['productos'])) {
    $num_cart = count($_SESSION['carrito']['productos']);
}
?>
