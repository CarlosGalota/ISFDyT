<?php
session_start();

// Verificar si el usuario ya tiene una sesión abierta
if(isset($_SESSION['id_usuario'])) {
    // Redirigir según el rol del usuario
    switch ($_SESSION['rol']) {
        case 1:
            // Profesor
            header("Location: profesor.php");
            exit();
        case 2:
            // Preceptor
            header("Location: preceptor.php");
            exit();
        case 3:
            // Alumno
            header("Location: alumno.php");
            exit();
        default:
            // Rol no válido
            header("Location: error.php");
            exit();
    }
}

require "conexion.php";

// Conexión a la base de datos
$mysqli = conectar();

// Obtener datos del formulario y sanitizar la entrada del usuario
$usuario = $mysqli->real_escape_string($_POST['usuario']);
$pass = $mysqli->real_escape_string($_POST['pass']);

// Validar usuario utilizando sentencia preparada
$query = "SELECT * FROM usuarios WHERE usuario = ? AND password = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("ss", $usuario, $pass);
$stmt->execute();
$result = $stmt->get_result();



if ($result->num_rows === 1) {
    // Usuario encontrado
    $user = $result->fetch_assoc();
    $_SESSION['id_usuario'] = $user['idUsuarios'];
    $_SESSION['nombre'] = $user['nombre'];
    $_SESSION['dni'] = $user['dni'];
    $_SESSION['apellido'] = $user['apellido'];
    $_SESSION['rol'] = $user['idRoles'];

    

    // Redirigir según el rol
    switch ($user['idRoles']) {
        case 1:
            // Profesor
            header("Location: profesor.php");
            exit();
        case 2:
            // Preceptor
            header("Location: preceptor.php");
            exit();
        case 3:
            // Alumno
            header("Location: alumno.php");
            exit();
        default:
            // Rol no válido
            header("Location: error.php");
            exit();
    }
} else {
    // Usuario no encontrado
    $_SESSION['error'] = "Usuario o contraseña incorrectos";
    header("Location: index.php");
    exit();
}
?>