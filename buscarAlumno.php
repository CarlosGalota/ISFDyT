<?php
session_start();
if (!isset($_SESSION['id_usuario']) || ($_SESSION['rol'] != 1 && $_SESSION['rol'] != 2)) {
    echo "ACCESO NO AUTORIZADO";
    exit();
}

require "conexion.php";
$conn = conectar();
$apellido = $_POST['apellido'] ?? '';

if (empty($apellido)) {
    redirigirConMensaje("El apellido no puede estar vacío.", $_SESSION['rol']);
    exit();
}

function redirigirConMensaje($mensaje, $rol) {
    $pagina = ($rol == 1) ? 'profesor.php' : 'preceptor.php';
    echo "<script>alert('$mensaje'); window.location.href = '$pagina';</script>";
    exit();
}


if ($_SESSION['rol'] == 1) { // Rol: Profesores
    $sql = "SELECT 
                *
            FROM 
                usuarios AS u
            INNER JOIN 
                notas AS n ON u.idUsuarios = n.idUsuarios
            INNER JOIN 
                materias_profesores AS mp ON n.idMaterias = mp.idMaterias
            WHERE 
                u.apellido = ? 
                AND mp.idUsuarios = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $apellido, $_SESSION['id_usuario']);

} else if ($_SESSION['rol'] == 2) { // Rol: Preceptores
    $sql = "SELECT 
                u.idUsuarios, 
                u.nombre, 
                u.apellido, 
                n.idMaterias, 
                n.parcial1, 
                n.parcial2, 
                n.final
            FROM 
                usuarios AS u
            INNER JOIN 
                notas AS n ON u.idUsuarios = n.idUsuarios
            INNER JOIN 
                materias_profesores AS mp ON n.idMaterias = mp.idMaterias
            INNER JOIN 
                materias AS m ON n.idMaterias = m.idMaterias
            WHERE 
                u.apellido = ? 
                AND m.idCarreras = ? 
                AND mp.idUsuarios = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $apellido, $_SESSION['carrera_id'], $_SESSION['id_usuario']);
}

$stmt->execute();
$result = $stmt->get_result();

// Si no hay resultados
if ($result->num_rows == 0) {
    redirigirConMensaje("No se encontró el alumno.", $_SESSION['rol']);
    exit();
}

// Construir el array de alumnos
$alumnos = [];
while ($registro = $result->fetch_assoc()) {
    $alumnos[] = $registro;
}
?>

<?php include("include/header.php") ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar Alumno</title>
</head>
<body>
    <div class="content">
        <h2>Resultados de la Búsqueda</h2>
        <?php if ($_SESSION['rol'] == 1): ?>
            <form method="GET" action="editarAlumno.php">
        <?php endif; ?>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>DNI</th>
                        <th>Materia</th>
                        <th>Parcial 1</th>
                        <th>Parcial 2</th>
                        <th>Final</th>
                        <?php if ($_SESSION['rol'] == 1): ?>
                            <th>Seleccionar</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alumnos as $alumno): ?>
                    <tr>
                        <td><?= htmlspecialchars($alumno['nombre']) ?></td>
                        <td><?= htmlspecialchars($alumno['apellido']) ?></td>
                        <td><?= htmlspecialchars($alumno['dni']) ?></td>
                        <td>
                            <?php
                            $matnom = "SELECT nombreMaterias FROM materias WHERE idMaterias = ?";
                            $stmtMat = $conn->prepare($matnom);
                            $stmtMat->bind_param("i", $alumno['idMaterias']);
                            $stmtMat->execute();
                            $resmat = $stmtMat->get_result()->fetch_assoc();
                            echo htmlspecialchars($resmat['nombreMaterias']);
                            ?>
                        </td>
                        <td><?= $alumno['parcial1'] !== null ? htmlspecialchars($alumno['parcial1']) : "No disponible" ?></td>
                        <td><?= $alumno['parcial2'] !== null ? htmlspecialchars($alumno['parcial2']) : "No disponible" ?></td>
                        <td><?= $alumno['final'] !== null ? htmlspecialchars($alumno['final']) : "No disponible" ?></td>
                        <?php if ($_SESSION['rol'] == 1): ?>
                            <td>
                                <input type="radio" name="idAlumno" value="<?= htmlspecialchars($alumno['idUsuarios']) ?>" required>
                                <input type="hidden" name="idMateria" value="<?= htmlspecialchars($alumno['idMaterias']) ?>">
                            </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if ($_SESSION['rol'] == 1): ?>
                <input type="submit" value="Editar Notas" class="botones">
            </form>
            <?php endif; ?>
            <br>
            <?php if ($_SESSION['rol'] == 1): ?>
                <a href="profesor.php"><button class="botones">Volver</button></a>
            <?php else: ?>
                <a href="preceptor.php"><button class="botones">Volver</button></a>
            <?php endif; ?>
        </div>
    </body>
    </html>
<?php include("include/footer.php") ?>
