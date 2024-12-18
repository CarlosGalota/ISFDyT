<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 2) {
    echo "ACCESO NO AUTORIZADO";
    exit();
}

if (!isset($_GET['idAlumno'])) {
    echo "No se ha seleccionado un alumno.";
    exit();
}

$idAlumno = intval($_GET['idAlumno']);

require "conexion.php";
$conn = conectar();



$sql = "SELECT 
            m.nombreMaterias, 
            n.parcial1, 
            n.parcial2, 
            n.final
        FROM 
            materias AS m
        INNER JOIN 
            notas AS n ON m.idMaterias = n.idMaterias
        WHERE 
            n.idUsuarios = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idAlumno);
$stmt->execute();
$result = $stmt->get_result();

$alumnoQuery = "SELECT nombre, apellido FROM usuarios WHERE idUsuarios = ?";
$stmtAlumno = $conn->prepare($alumnoQuery);
$stmtAlumno->bind_param("i", $idAlumno);
$stmtAlumno->execute();
$alumnoResult = $stmtAlumno->get_result();
$alumno = $alumnoResult->fetch_assoc();
?>

<?php include("include/header.php") ?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Materias del Alumno</title>
</head>
<body>
    <div class="content">
        <h2>Materias de <?= htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellido']) ?></h2>

        <table>
            <thead>
                <tr>
                    <th>Materia</th>
                    <th>Parcial 1</th>
                    <th>Parcial 2</th>
                    <th>Final</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($materia = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($materia['nombreMaterias']) ?></td>
                            <td><?= htmlspecialchars($materia['parcial1'] ?? 'No disponible') ?></td>
                            <td><?= htmlspecialchars($materia['parcial2'] ?? 'No disponible') ?></td>
                            <td><?= htmlspecialchars($materia['final'] ?? 'No disponible') ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No hay materias registradas para este alumno.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="button">
            <a href="calificacionesPre.php"><button class="botones">Volver a Lista de Alumnos</button></a>
        </div>
                </div>
</body>
</html>

<?php
$conn->close();
?>


<?php include("include/footer.php") ?>