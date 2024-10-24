<?php

require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$errors = [];

if (!empty($_POST)) {
    $email = trim($_POST['email']);
    
    if (esNulo([$email])) {
        $errors[] = "Debe llenar todos los campos";
    }
    
    if (!esEmail($email)) {
        $errors[] = "La dirección de correo no es válida";
    }
    if(count($errors)==0){
      if(emailExiste($email,$con)){
        $sql = $con->prepare("SELECT usuarios.id, clientes.nombres FROM usuarios
        INNER JOIN clientes ON usuarios.id_cliente=clientes.id
        WHERE clientes.email LIKE ? LIMIT 1");
        $sql->execute([$email]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        $user_id = $row['id'];
        $nombres= $row['nombres'];

        $token = solicitaPassword($user_id, $con);
        if($token !== null){
          require 'clases/Mailer.php';
            $mailer = new Mailer();

            $url = SITE_URL . '/reset_password.php?id=' . $user_id. '&token=' . $token;
            $asunto = "Recuperar password - Tech Shop";
            $cuerpo = "Estimado $nombres: <br> Si has solicitado el cambio de tu contraseña da clic en el 
            siguiente link <a href='$url'>$url</a>";
            $cuerpo.="<br>Si no hiciste esta solicitud puedes ignorar este correo.";

            if ($mailer->enviarEmail($email, $asunto, $cuerpo)) {
              echo "<p><b>Correo enviado</b></p>";
              echo "<p>Hemos enviado un correo electrónico a la dirección email para restablecer la contraseña</p>";
              exit;
          }
        }
    }else{
      $errors[]="No existe una cuenta asociada a esta dirección de correo.";
    }
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
      <div class="ms-auto">
        <a href="checkout.php" class="btn btn-primary">
          <i class="bi bi-cart"></i>
          <span id="num_cart" class="badge bg_secondary"><?php echo $num_cart ?></span>
        </a>
      </div>
      
      <button class="navbar-toggler ms-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </div>
</header>

<main class="form-login m-auto pt-4">
    <h3>Recuperar contraseña</h3>
    <?php mostrarMensajes($errors);?>

    <form action="recupera.php" method="post" class="row g-3" autocomplete="off">

      <div class="form-floating">
        <input class="form-control" type="email" name="email" id="email"
        placeholder="Correo Electrónico" required>
        <label for="email">Correo Electrónico</label>
      </div>

      <div class="d-grid gap-3 col-12">
            <button type="submit" class="btn btn-primary">Continuar</button>
        </div>

        <div class="col-12">
            ¿No tiene cuenta? <a href="registro.php">Registrate aquí</a>
        </div>

    </form>
</main>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
crossorigin="anonymous"></script>


</body>
</html>