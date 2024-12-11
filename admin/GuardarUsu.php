<?php
require "conexion.php";
$conn = conectar();

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$usuario = $_POST['usuario'];
$dni = $_POST['dni'];
$pass = $_POST['pass'];
$rol = $_POST['rol'];
$carrera = isset($_POST['carrera']) ? $_POST['carrera'] : null;
$materia = isset($_POST['materia']) ? $_POST['materia'] : null;

// Insertar usuario
$sql = "INSERT INTO usuarios (nombre, apellido,usuario, dni, password,idRoles) VALUES ('$nombre', '$apellido','$usuario', '$dni', '$pass','$rol')";
if (mysqli_query($conn, $sql)) {
    // Obtener el ID del usuario insertado
    $usuario_id = mysqli_insert_id($conn);
    
    // Si el rol es Profesor, insertar en la tabla materias_usuarios
    if ($rol == 2 && $materia) {
        $sql_materia = "INSERT INTO Materias_Profesores (idProfesor, idMaterias) VALUES ('$usuario_id', '$materia')";
        mysqli_query($conn, $sql_materia);
    }
    if ($rol == 1 && $carrera) {
        $sql_carrera = "INSERT INTO Usuarios_Carreras (idUsuarios, idCarreras) VALUES ('$usuario_id', '$carrera')";
        mysqli_query($conn, $sql_carrera);
    }

    header("Location: admin.php?ok");
} else {
    header("Location: admin.php?error");
}
?>
