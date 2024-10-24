<?php

require 'config/config.php';

session_start();
session_unset(); // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión
header("Location: index.php"); // Redirige a la página principal o de inicio
exit();
?>