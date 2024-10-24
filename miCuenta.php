<?php

require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$errors = [];
$user_id = $_SESSION['user_id'] ?? null; // Obtén el ID del usuario desde la sesión
$user_data = [];


if ($user_id) {
    // Obtener información del cliente basado en el ID del usuario
    $sql = $con->prepare("SELECT c.nombres, c.apellidos, c.email, c.telefono, c.dui 
                           FROM clientes c 
                           JOIN usuarios u ON c.id = u.id_cliente 
                           WHERE u.id = ?");
    $sql->execute([$user_id]);
    $user_data = $sql->fetch(PDO::FETCH_ASSOC);
}

if (!$user_data) {
    $errors[] = "No se encontró información del usuario.";
}

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
        <h1>Mi Cuenta</h1>
        
        <?php mostrarMensajes($errors); ?>

        <?php if ($user_data): ?>
            <h2>Información del Usuario</h2>
            <ul class="list-group">
                <li class="list-group-item"><strong>Nombres:</strong> <?php echo htmlspecialchars($user_data['nombres']); ?></li>
                <li class="list-group-item"><strong>Apellidos:</strong> <?php echo htmlspecialchars($user_data['apellidos']); ?></li>
                <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($user_data['email']); ?></li>
                <li class="list-group-item"><strong>Teléfono:</strong> <?php echo htmlspecialchars($user_data['telefono']); ?></li>
                <li class="list-group-item"><strong>DUI:</strong> <?php echo htmlspecialchars($user_data['dui']); ?></li>
            </ul>
        <?php else: ?>
            <p>No hay información disponible.</p>
        <?php endif; ?>
        <div class="col-12">
            <br><a href="recupera.php">Cambiar mi contraseña</a>
        </div>
    </div>
</main>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
crossorigin="anonymous"></script>


</body>
</html>