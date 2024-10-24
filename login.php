<?php

require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$proceso = isset($_GET['pago']) ? 'pago' : 'login';

$errors = [];

if (!empty($_POST)) 
{
        $usuario = trim($_POST['usuario']);
        $password = trim($_POST['password']);
        $proceso = $_POST['proceso'] ?? 'login';
        
        if (esNulo([$usuario, $password])) {
            $errors[] = "Debe llenar todos los campos";
        }

        if (count($errors) == 0) {
        $errors[] = login($usuario, $password, $con, $proceso);
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
    <!--Menu de navegacion-->
    <?php include  'menu.php';?>

<main class="form-login m-auto pt-4">
    <h2>Iniciar sesión</h2>

    <?php mostrarMensajes($errors);?>

    <form class="row g-3" action="login.php" method="post" autocomplete="off">

    <input type="hidden" name="proceso" value="<?php echo $proceso;?>">

        <div class="form-floating">
            <input class="form-control" type="text" name="usuario" id="usuario" placeholder="Usuario" >
            <label for="usuario">Usuario</label>
        </div>

        <div class="form-floating">
            <input class="form-control" type="password" name="password" id="password" placeholder="Contraseña" >
            <label for="password">Contraseña</label>
        </div>

        <div class="col-12">
            <a href="recupera.php">¿Olvidaste tu contraseña?</a>
        </div>

        <hr>

        <div class="d-grid gap-3 col-12">
            <button type="submit" class="btn btn-primary">Ingresar</button>
        </div>

        <div class="col-12">
            ¿No tiene cuenta? <a href="registro.php">Registrate aquí</a>
        </div>

</main>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
crossorigin="anonymous"></script>

<script>
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('focus', function() {
            this.classList.add('active');
        });
        
        input.addEventListener('blur', function() {
            if (this.value === '') {
                this.classList.remove('active');
            }
        });
    });
</script>
    
</body>
</html>