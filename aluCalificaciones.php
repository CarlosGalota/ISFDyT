<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 3) {
    echo "ACCESO NO AUTORIZADO";
    exit();
}

require "conexion.php";
?>

<?php include("include/header.php") ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calificaciones del Alumno</title>
   
</head>
<body>
    <div class="content">
        <h1>Calificaciones</h1>
        <?php
        $conn = conectar();

        $sql = "SELECT m.nombre_materia, n.parcial1, n.parcial2, n.final
                FROM notas n
                JOIN materias m ON n.id_materia = m.id_materia
                WHERE n.id_alumno = " . $_SESSION['id_usuario'] . ";";

        $resulset = mysqli_query($conn, $sql);

        // Verificar si hay notas disponibles
        if (mysqli_num_rows($resulset) > 0) {
        ?>
            <table>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>DNI</th>
                    <th>Materia</th>
                    <th>Parcial 1</th>
                    <th>Parcial 2</th>
                    <th>Nota final</th>
                </tr>
                <?php
                while ($registro = mysqli_fetch_assoc($resulset)) {
                ?>
                    <tr>
                        <td><?= htmlspecialchars($_SESSION['nombre']) ?></td>
                        <td><?= htmlspecialchars($_SESSION['apellido']) ?></td>
                        <td><?= htmlspecialchars($_SESSION['dni']) ?></td>
                        <td><?= htmlspecialchars($registro['nombre_materia']) ?></td>
                        <td><?= htmlspecialchars($registro['parcial1']) ?></td>
                        <td><?= htmlspecialchars($registro['parcial2']) ?></td>
                        <td><?= htmlspecialchars($registro['final']) ?></td>
                    </tr>
                <?php
                }
                ?>
            </table>
            <div class="button">
                <a href="fpdf/ReporteCalificacion.php" target="_blank"><button class="botones">Generar Reporte</button></a>
            </div>
        <?php
        } else {
            echo "<center><h3>Todavía no hay notas cargadas.</h3></center>";
        }
        ?>

            <a href="alumno.php"><button class="botones">Volver</button></a>
    </div>

<?php include("include/footer.php") ?>
</body>
</html>