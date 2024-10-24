<?php

function esNulo(array $parametros) {
    foreach ($parametros as $parametro) {
        if (strlen(trim($parametro)) < 1) {
            return true;
        }
    }
    return false;
}

/*function esEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}*/

/* function validaPassword($password, $repassword) {
    return strcmp($password, $repassword) === 0;
}*/

/*
function generarToken() {
    // Genera un token único utilizando una combinación de random_bytes y bin2hex para mayor seguridad
    return bin2hex(random_bytes(16));
}*/

/*
function registraCliente(array $datos, $con) {
    try {
        $sql = $con->prepare("INSERT INTO clientes (nombres, apellidos, email, telefono, dui, estatus, fecha_alta) VALUES (?, ?, ?, ?, ?, 1, NOW())");
        // Ejecuta la consulta con los datos proporcionados
        if ($sql->execute($datos)) {
            // Retorna el último ID insertado si la ejecución fue exitosa
            return $con->lastInsertId();
        }
    } catch (PDOException $e) {
        // Registra el error si ocurre una excepción
        error_log("Error en registraCliente: " . $e->getMessage());
    }
    // Retorna 0 en caso de fallo
    return 0;
}*/

/*
function registraUsuario($datos, $con) {
    $stmt = $con->prepare("INSERT INTO usuarios (usuario, password, token, id_cliente) VALUES (?, ?, ?, ?)");
    $stmt->execute($datos);
    return $con->lastInsertId(); // Esto devuelve el ID del último usuario insertado
}*/

// Función para verificar si un nombre de usuario ya existe en la base de datos.
function usuarioExiste($usuario, $con) {
    try {
        // Prepara la consulta para buscar un usuario que coincida con el proporcionado.
        $sql = $con->prepare("SELECT id FROM usuarios WHERE usuario = ? LIMIT 1");
        // Ejecuta la consulta con el nombre de usuario.
        $sql->execute([$usuario]);
        // Si la consulta devuelve al menos un resultado, el usuario ya existe.
        return $sql->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Error en usuarioExiste: " . $e->getMessage());
    }
    return false;
}

// Función para verificar si un email ya está registrado en la base de datos.
function emailExiste($email, $con) {
    try {
        // Prepara la consulta para buscar un email que coincida con el proporcionado.
        $sql = $con->prepare("SELECT id FROM clientes WHERE email = ? LIMIT 1");
        // Ejecuta la consulta con el email.
        $sql->execute([$email]);
        // Si la consulta devuelve al menos un resultado, el email ya está registrado.
        return $sql->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Error en emailExiste: " . $e->getMessage());
    }
    return false;
}

function validaToken($id, $token, $con) {
    $stmt = $con->prepare("SELECT * FROM usuarios WHERE id = ? AND token = ? AND activacion = 0");
    $stmt->execute([$id, $token]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Marca el token como utilizado
        $stmt = $con->prepare("UPDATE usuarios SET activacion = 1 WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    }
    return false;
}




function solicitaPassword($user_id, $con){
    $token = generarToken();
    $sql = $con->prepare("UPDATE usuarios SET token_password=?, password_request=1 WHERE id = ?");
    if($sql->execute([$token,$user_id])){
        return $token;
    }
    return null;
}

// Función para mostrar mensajes de error en formato HTML.
function mostrarMensajes(array $errors) {
    if (count($errors) > 0) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert"><ul>';
        foreach ($errors as $error) {
            echo '<li>' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}

function activarUsuarios($id, $con)
{
    $sql = $con->prepare("UPDATE usuarios SET activacion = 1, token = '' WHERE id = ?");
    return $sql->execute([$id]);
}

function login($usuario, $password, $con) {
    $sql = $con->prepare("SELECT id, usuario, password, nombre FROM admin WHERE usuario LIKE ? AND activo = 1 LIMIT 1");
    $sql->execute([$usuario]);

    // Asignar el resultado a la variable $row
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    
    // Verificar si se encontró un resultado
    if ($row) {
            // Verificar la contraseña
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['nombre'];
                $_SESSION['user_type'] = 'admin';
                header('Location: inicio.php');
                exit;
                
                /*
                // Obtener el correo electrónico del cliente
                $sql_email = $con->prepare("SELECT email FROM clientes WHERE id = (SELECT id_cliente FROM usuarios WHERE id = ? LIMIT 1)");
                $sql_email->execute([$row['id']]);
                $user_email = $sql_email->fetch(PDO::FETCH_ASSOC);
                

                // Almacena el correo en la sesión
                if ($user_email) {
                    $_SESSION['user_email'] = $user_email['email'];
                }
                exit;
            }*/
    }   
    
    }return 'El usuario y/o contraseña son incorrectos.';
}


/*
function esActivo($usuario, $con){
    $sql = $con->prepare("SELECT activacion FROM usuarios WHERE usuario LIKE ? LIMIT 1");
    $sql->execute([$usuario]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    if($row['activacion'] == 1){
        return true;
    }
    return false;
}*/

function verificaTokenRequest($user_id,$token,$con){
    $sql = $con->prepare("SELECT id FROM usuarios WHERE id = ? AND token_password LIKE ? AND password_request = 1 LIMIT 1");
    $sql->execute([$user_id,$token]);
    if($sql->fetchColumn() > 0){
        return true;
    }
    return false;
}

function actualizaPassword($user_id,$password,$con){
    $sql = $con->prepare("UPDATE usuarios SET password=?, token_password = '', password_request = 0 WHERE id = ?");
    if($sql->execute([$password,$user_id])){
        return true;
    }
    return false;
}
?>

