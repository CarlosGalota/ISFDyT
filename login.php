<?php
session_start();

// Verificar si el usuario ya tiene una sesión abierta
if(isset($_SESSION['id_usuario'])) {
    // Redirigir según el rol del usuario
    switch ($_SESSION['rol']) {
        case 1:
            // Profesor
            header("Location: alumno.php");
            exit();
        case 2:
            // Preceptor
            header("Location: profesor.php");
            exit();
        case 3:
            // Alumno
            header("Location: Preceptor.php");
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
$email = $mysqli->real_escape_string($_POST['email']);
$pass = $mysqli->real_escape_string($_POST['pass']);

// Validar usuario utilizando sentencia preparada
$query = "SELECT * FROM usuarios WHERE email = ? AND pass = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("ss", $email, $pass);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Usuario encontrado
    $user = $result->fetch_assoc();
    $_SESSION['id_usuario'] = $user['id_usuario'];
    $_SESSION['nombre'] = $user['nombre'];
    $_SESSION['dni'] = $user['dni'];
    $_SESSION['apellido'] = $user['apellido'];
    $_SESSION['carrera_id'] = $user['id_carrera'];
    $_SESSION['rol'] = $user['rol'];

    // Redirigir según el rol
    switch ($user['rol']) {
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