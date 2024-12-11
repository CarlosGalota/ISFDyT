
<?php
session_start();
if (!isset($_SESSION['id_usuario']) || ($_SESSION['rol'] != 1 && $_SESSION['rol'] != 2)) {
    echo "ACCESSO NO AUTORIZADO";
    exit();
}?>
<?php include("include/header.php") ?>

<?php
require "conexion.php";
$conn = conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idAlumno = $_POST['idAlumno'];
    $idMateria = $_POST['idMateria'];
    $parcial1 = $_POST['parcial1'];
    $parcial2 = $_POST['parcial2'];
    $final = $_POST['final'];

    // Validar que las notas estén entre 0 y 10
    if (($parcial1 >= 0 && $parcial1 <= 10) && ($parcial2 >= 0 && $parcial2 <= 10) && ($final >= 0 && $final <= 10)) {
        $sqlActualizarNotas = "UPDATE notas SET parcial1 = ?, parcial2 = ?, final = ? WHERE id_alumno = ? AND id_materia = ?";
        $stmt = $conn->prepare($sqlActualizarNotas);
        $stmt->bind_param("iiiii", $parcial1, $parcial2, $final, $idAlumno, $idMateria);

        if ($stmt->execute()) {
            echo '<script>alert("Notas actualizadas correctamente."); window.location.href = "profesor.php";</script>';
        } else {
            echo '<script>alert("Error al actualizar las notas.");</script>';
        }
    } else {
        echo '<script>alert("Las notas deben estar entre 0 y 10.");</script>';
    }
}

if (isset($_GET['idAlumno']) && isset($_GET['idMateria'])) {
    $idAlumno = $_GET['idAlumno'];
    $idMateria = $_GET['idMateria'];

    $sql = "SELECT u.nombre, u.apellido, u.dni, n.parcial1, n.parcial2, n.final
            FROM usuarios u
            JOIN notas n ON u.id_usuario = n.id_alumno
            WHERE u.id_usuario = ? AND n.id_materia = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $idAlumno, $idMateria);
    $stmt->execute();
    $result = $stmt->get_result();
    $alumno = $result->fetch_assoc();
}
?>
<head>
    <meta charset="UTF-8">
    <title>Editar Notas</title>
</head>
<body>
    <center>
        <h2>Editar Notas del Alumno</h2>
        <form method="POST" action="">
            <input type="hidden" name="idAlumno" value="<?= $idAlumno ?>">
            <input type="hidden" name="idMateria" value="<?= $idMateria ?>">
            <table>
                <tr>
                    <th>Nombre:</th>
                    <th>Apellido:</th>
                    <th>DNI:</th>
                    <th>Parcial 1:</th>
                    <th>Parcial 2:</th>
                    <th>Final:</th>
                </tr>
                <tr>
                <td><?= $alumno['nombre'] ?></td>
                <td><?= $alumno['apellido'] ?></td>
                <td><?= $alumno['dni'] ?></td>
                <td><input type="number" name="parcial1" value="<?= $alumno['parcial1'] ?>" min="0" max="10" required></td>
                <td><input type="number" name="parcial2" value="<?= $alumno['parcial2'] ?>" min="0" max="10" required></td>
                <td><input type="number" name="final" value="<?= $alumno['final'] ?>" min="0" max="10" required></td>
                </tr>
               <div><p>Si el Alumno no rindio final, agregar un 0 en el campo del mismo</p></div>
            </table>
            <input type="submit" value="Guardar">
        </form>
        <br>
        <a href="profesor.php"><button class="botones">Volver</button></a>
    </center>
    <?php include("include/footer.php") ?>
