
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
        $sqlActualizarNotas = "UPDATE notas SET parcial1 = ?, parcial2 = ?, final = ? WHERE idUsuarios = ? AND idMaterias = ?";
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
    $idAlumno = $_GET['idAlumno'] ?? null;
    $idMateria = $_GET['idMateria'] ?? null;
    $nombre = $_GET['nombre'] ?? '';
    $dni = $_GET['dni'] ?? '';
    $apellido = $_GET['apellido'] ?? '';
    $parcial1 = $_GET['parcial1'] ?? '';
    $parcial2 = $_GET['parcial2'] ?? '';
    $final = $_GET['final'] ?? '';
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
        <input type="hidden" name="idAlumno" value="<?= htmlspecialchars($idAlumno) ?>">
        <input type="hidden" name="idMateria" value="<?= htmlspecialchars($idMateria) ?>">
        <input type="hidden" name="nombre" value="<?= htmlspecialchars($nombre) ?>">
        <input type="hidden" name="apellido" value="<?= htmlspecialchars($apellido) ?>">
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
            <td><?= htmlspecialchars($nombre) ?></td>
            <td><?= htmlspecialchars($apellido) ?></td>
            <td><?=  htmlspecialchars($dni) ?></td>
            <td><input type="number" name="parcial1" value="<?= htmlspecialchars($parcial1) ?>" min="0" max="10" required></td>
            <td><input type="number" name="parcial2" value="<?= htmlspecialchars($parcial2) ?>" min="0" max="10" required></td>
            <td><input type="number" name="final" value="<?= htmlspecialchars($final) ?>" min="0" max="10" required></td>
        </tr>
        </table>
        <div><p>Si el Alumno no rindió final, agregar un 0 en el campo del mismo</p></div>
        <input type="submit" value="Guardar">
        </form>
        <br>
        <a href="profesor.php"><button class="botones">Volver</button></a>
    </center>
    <?php include("include/footer.php") ?>
