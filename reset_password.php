<?php

require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

$user_id = $_GET['id'] ?? $_POST['user_id'] ?? '';
$token = $_GET['token'] ?? $_POST['token'] ?? '';

if($user_id == '' || $token == ''){
  header("Location: index.php");
  exit;
}

$db = new Database();
$con = $db->conectar();

$errors = [];

if(!verificaTokenRequest($user_id,$token,$con)){
  echo "No se pudo verificar la información";
  exit;
}

if (!empty($_POST)) {
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);
    
    if (esNulo([$user_id,$token, $password, $repassword])) {
        $errors[] = "Debe llenar todos los campos";
    }
    
    if (!validaPassword($password, $repassword)) {
        $errors[] = "Las contraseñas no coinciden";
    }
    
    if (count($errors) == 0){
      $pass_hash = password_hash($password, PASSWORD_DEFAULT);
      if(actualizaPassword($user_id,$pass_hash,$con)){
        echo "Contraseña modificada.<br><a href='login.php'>Iniciar sesión</a>";
        exit;
    }
      $errors[] ="Error al modificar la contraseña. Intentalo de nuevo.";
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
        
<header data-bs-theme="dark">
  <div class="collapse text-bg-dark" id="navbarHeader">
    <div class="container">
      <div class="row">
        <div class="col-sm-8 col-md-7 py-4">
        <h4>Acerca de nosotros</h4>
          <p class="text-body-secondary">Somos tienda líder en tecnología ubicada en el corazón de San Salvador. Fundada en 2010, TechZone ha crecido rápidamente hasta convertirse en el destino preferido para los entusiastas de la tecnología en el país. Ofrecemos una amplia gama de productos, desde las últimas laptops y smartphones hasta accesorios de gaming y componentes para PC. Nuestro compromiso con la calidad y el servicio al cliente nos distingue, garantizando que siempre encuentres lo mejor en tecnología a precios competitivos.</p>
        </div>
        <div class="col-sm-4 offset-md-1 py-4">
          <h4>Contact</h4>
          <ul class="list-unstyled">
            <li><a href="#" class="text-white">Follow on Twitter</a></li>
            <li><a href="#" class="text-white">Like on Facebook</a></li>
            <li><a href="#" class="text-white">Email me</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container d-flex align-items-center">
      <a href="#" class="navbar-brand d-flex align-items-center"> 
        <strong>TechShop</strong>
      </a>
      <ul class="navbar-nav d-flex flex-row ms-3">
        <li class="nav-item">
            <a href="index.php" class="nav-link active">Catálogo</a>
        </li>

        <!--<li class="nav-item ms-3">
            <a href="#" class="nav-link ">Contacto</a>
        </li>-->
      </ul>
      <<div class="ms-auto">

<a href="checkout.php" class="btn btn-primary me-2">
  <i class="bi bi-cart"></i>
  <span id="num_cart" class="badge bg_secondary"><?php echo $num_cart ?></span>
</a>

<!--SESION ACTUAL DE USUARIO-->
<?php if(isset($_SESSION['user_id'])){ ?>
  <a href="#" class="btn btn-success"><i class="bi bi-person"></i> <?php echo $_SESSION['user_name']; ?></a>
  <a href="logout.php" class="btn btn-primary me-2">Cerrar sesión</a>
  <?php } else { ?>
    <a href="login.php" class="btn btn-success"><i class="bi bi-person"></i> Ingresar</a>
    <a href="registro.php" class="btn btn-success"><i class="bi bi-person-vcard"></i> Registrarse</a><?php } ?>
</div>  
      
      <button class="navbar-toggler ms-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </div>
</header>

<main class="form-login m-auto pt-4">
    <h3>Cambiar contraseña</h3>
    <?php mostrarMensajes($errors);?>

    <form action="reset_password.php" method="post" class="row g-3" autocomplete="off">

    <input type="hidden" name="user_id" id="user_id" value="<?= $user_id; ?>"/>
    <input type="hidden" name="token" id="token" value="<?= $token; ?>"/>

      <div class="form-floating">
        <input class="form-control" type="password" name="password" id="password"
        placeholder="Nueva Contraseña" required>
        <label for="email">Nueva contraseña</label>
      </div>

      <div class="form-floating">
        <input class="form-control" type="password" name="repassword" id="repassword"
        placeholder="Confirmar Contraseña" required>
        <label for="email">Confirmar contraseña</label>
      </div>

      <div class="d-grid gap-3 col-12">
            <button type="submit" class="btn btn-primary">Continuar</button>
        </div>

        <div class="col-12">
            <a href="login.php">Iniciar sesión</a>
        </div>

    </form>
</main>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
crossorigin="anonymous"></script>


</body>
</html>