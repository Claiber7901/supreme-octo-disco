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
    
    if (count($errors) == 0) {
        $id_cliente = registraCliente([$nombres, $apellidos, $email, $telefono, $dui], $con);
    
        if ($id_cliente > 0) {
            require 'clases/Mailer.php';
            $mailer = new Mailer();
            $token = generarToken();
            
            // Aquí es donde necesitas realizar el registro del usuario
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $id_usuario = registraUsuario([$usuario, $pass_hash, $token, $id_cliente], $con);
            
            // Asegúrate de usar el ID del usuario en la URL
            $url = SITE_URL . "activa_cliente.php?id=$id_usuario&token=$token";
            
            $asunto = "Activar cuenta - Tienda online";
            $cuerpo = "Estimado $nombres $apellidos: <br> Para continuar con el proceso de registro es indispensable dar click en la siguiente liga <a href='$url'>Activar cuenta</a>";
    
            if ($mailer->enviarEmail($email, $asunto, $cuerpo)) {
                echo "Para terminar el proceso de registro siga las instrucciones que le hemos enviado a la dirección de correo electrónico $email. <br><a href='index.php'>Volver a TechShop</a>";
                exit;
            }
        } else {
            $errors[] = "Error al registrar cliente";
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
        
<?php include  'menu.php';?>

<main>
    <div class="container">
    <h2>Datos del cliente</h2>

    <?php mostrarMensajes($errors);?>


<form class="row g-3" action="registro.php" method="post" autocomplete="off">
    <div class="col-md-6">
        <label for="nombres"><span class="text-danger">*</span> Nombres</label>
        <input type="text" name="nombres" id="nombres" class="form-control" placeholder="John" required>
    </div>
    <div class="col-md-6">
        <label for="apellidos"><span class="text-danger">*</span> Apellidos</label>
        <input type="text" name="apellidos" id="apellidos" class="form-control" placeholder="Doe" required>
    </div>
    <div class="col-md-6">
    <label for="email"><span class="text-danger">*</span> Correo electrónico</label>
    <input type="email" name="email" id="email" class="form-control" placeholder="myemailexample@email.com"required>
    <span id="validaEmail" class="text-danger"></span>
    </div>

    <div class="col-md-6">
        <label for="telefono"><span class="text-danger">*</span> telefono</label>
        <input type="tel" name="telefono" id="telefono" class="form-control" placeholder="12345678"required>
    </div>
    <div class="col-md-6">
    <label for="dui"><span class="text-danger">*</span> DUI</label>
    <input type="text" name="dui" id="dui" class="form-control" placeholder="123456789" required>
</div>

<div class="col-md-6">
    <label for="usuario"><span class="text-danger">*</span> Usuario</label>
    <input type="text" name="usuario" id="usuario" class="form-control" placeholder="JohnCool" required>
    <span id="validaUsuario" class="text-danger"></span>
</div>

<div class="col-md-6">
    <label for="password"><span class="text-danger">*</span> Contraseña</label>
    <input type="password" name="password" id="password" class="form-control" required>
</div>
<div class="col-md-6">
    <label for="repassword"><span class="text-danger">*</span> Confirmar Contraseña</label>
    <input type="password" name="repassword" id="repassword" class="form-control" required>
</div>
<i><b>Nota:</b> Los campos con asterisco son obligatorios</i>

<div class="col-12">
    <button type="submit" class="btn btn-primary">Registrar</button>
</div>

</form>

    </div>
</main>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
crossorigin="anonymous"></script>

<script>
    let txtUsuario = document.getElementById('usuario');
    txtUsuario.addEventListener("blur", function() {
        existeUsuario(txtUsuario.value)
    }, false);

    let txtEmail = document.getElementById('email');
    txtEmail.addEventListener("blur", function() {
        existeEmail(txtEmail.value)
    }, false);


    function existeUsuario(usuario) {
        let url = "clases/clienteAjax.php";
        let formData = new FormData();
        formData.append("action", "existeUsuario");
        formData.append("usuario", usuario);

        fetch(url, {
            method: 'POST',
            body: formData
        }).then(response => response.json())
        .then(data => {

            if (data.ok) {
             document.getElementById('usuario').value = '';
             document.getElementById('validaUsuario').innerHTML = 'Usuario no disponible';
            } else {
             document.getElementById('validaUsuario').innerHTML = '';   
            }

          });
            }

            function existeEmail(email) {
    let url = "clases/clienteAjax.php";
    let formData = new FormData();
    formData.append("action", "existeEmail");
    formData.append("email", email);

    fetch(url, {
        method: 'POST',
        body: formData
    }).then(response => response.json())
    .then(data => {
        if (data.ok) {
            document.getElementById('email').value = '';
            document.getElementById('validaEmail').innerHTML = 'Email no disponible';
        } else {
            document.getElementById('validaEmail').innerHTML = '';
        }
    });
}
        
</script>


</body>
</html>