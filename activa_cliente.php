<?php

require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

$id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';
$token = isset($_GET['token']) ? htmlspecialchars($_GET['token']) : '';

if ($id == '' || $token == '') {
    header("Location: index.php");
    exit;
}

$db = new Database();
$con = $db->conectar();

$resultado = validaToken($id, $token, $con);
if ($resultado) {
    echo "Cuenta activada con éxito. <br><a href='index.php'>Volver a TechShop</a>";
} else {
    echo "Error al activar la cuenta. Token inválido o ya utilizado. <br><a href='index.php'>Volver a TechShop</a>";
}
?>
