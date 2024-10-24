<?php

require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$errors = [];

if (!empty($_POST)) {
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $dui = trim($_POST['dui']);
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);
    
    if (esNulo([$nombres, $apellidos, $email, $telefono, $dui, $usuario, $password, $repassword])) {
        $errors[] = "Debe llenar todos los campos";
    }
    
    if (!esEmail($email)) {
        $errors[] = "La dirección de correo no es válida";
    }
    
    if (!validaPassword($password, $repassword)) {
        $errors[] = "Las contraseñas no coinciden";
    }
    
    if (usuarioExiste($usuario, $con)) {
        $errors[] = "El nombre de usuario $usuario ya existe";
    }
    
    if (emailExiste($email, $con)) {
        $errors[] = "El correo electrónico $email ya existe";
    }
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Tienda Online</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link href="css/estilos.css" rel="stylesheet">
</head>
<body>
        
<?php include  'menu.php';?>

<main>
    <div class="container">
        <h1>Bienvenido a TechShop</h1>
         <!-- Carrusel de imágenes -->
         <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="images/TechShopIndex/imagen1.jpg" class="d-block w-100" alt="Imagen 1">
                </div>
                <div class="carousel-item">
                    <img src="images/TechShopIndex/imagen2.jpg" class="d-block w-100" alt="Imagen 2">
                </div>
                <div class="carousel-item">
                    <img src="images/TechShopIndex/imagen3.jpg" class="d-block w-100" alt="Imagen 3">
                </div>
                <!-- Agrega más imágenes según sea necesario -->
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
        
        <p><i><br>En TechShop, tu tienda en línea de tecnología, ofrecemos una amplia gama de productos electrónicos de alta calidad, incluyendo laptops, gpus, cpus, discos duros, auriculares, perifericos, accesorios y mucho más. Nuestro objetivo es brindarte las mejores ofertas y un servicio excepcional.</i></p>

        <h2>¿Por qué elegir TechShop?</h2>
        <ul>
            <li><strong>Variedad:</strong> Encuentra todo lo que necesitas en un solo lugar.</li>
            <li><strong>Precios competitivos:</strong> Te ofrecemos los mejores precios del mercado.</li>
            <li><strong>Envío rápido:</strong> Disfruta de un envío eficiente y seguro a la puerta de tu casa.</li>
            <li><strong>Atención al cliente:</strong> Nuestro equipo está listo para ayudarte en cualquier momento.</li>
        </ul>
        
        <h2>Únete a nuestra comunidad</h2>
        <p>Regístrate ahora para recibir novedades, ofertas exclusivas y más. ¡Te esperamos en TechShop!</p>

        <?php
        // Mostrar errores si existen
        if (!empty($errors)) {
            echo '<div class="alert alert-danger" role="alert">';
            foreach ($errors as $error) {
                echo "<p>$error</p>";
            }
            echo '</div>';
        }
        ?>
    </div>
</main>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
crossorigin="anonymous"></script>


</body>
</html>