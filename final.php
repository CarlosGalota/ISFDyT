<?php
require "conexion.php";
session_start();

// Verificar si el usuario ha iniciado sesión como profesor
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 1) {
    echo "ACCESSO NO AUTORIZADO";
    exit();
}

$conn = conectar();
$idProfesor = $_SESSION['id_usuario'];

// Obtener la lista de materias del profesor
$sqlMaterias = "SELECT m.id_materia, m.nombre_materia 
                FROM profesores_materias pm
                JOIN materias m ON pm.id_materia = m.id_materia
                WHERE pm.id_profesor = ?";
$stmtMaterias = $conn->prepare($sqlMaterias);
$stmtMaterias->bind_param("i", $idProfesor);
$stmtMaterias->execute();
$resultMaterias = $stmtMaterias->get_result();

$errorMessage = '';

// Procesar el formulario si se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guardar'])) {
    $materia = $_POST['materia'];
    
    $stmtUpdate = $conn->prepare("UPDATE notas SET final = ? WHERE id_alumno = ? AND id_materia = ? AND id_profesor = ?");

    $valid = true;
    foreach ($_POST['notas'] as $idAlumno => $notas) {
        $final = $notas['nota3'] ?? null;

        // Validar las notas
        if ($final !== null && ($final < 0 || $final > 10)) {
            $valid = false;
            $errorMessage = 'Las notas deben estar entre 0 y 10.';
            break;
        }

        if ($valid && $final !== null) {
            $stmtUpdate->bind_param("iiii", $final, $idAlumno, $materia, $idProfesor);
            $stmtUpdate->execute();
        }
    }

    if ($valid) {
        echo '<script>alert("Notas actualizadas correctamente.")</script>';
    } else {
        echo '<script>alert("' . $errorMessage . '")</script>';
    }
}

// Función para obtener la nota de un alumno en una materia específica
function obtenerNota($conn, $idAlumno, $idMateria, $tipoParcial) {
    $sqlNota = "SELECT $tipoParcial FROM notas WHERE id_alumno = ? AND id_materia = ?";
    $stmtNota = $conn->prepare($sqlNota);
    $stmtNota->bind_param("ii", $idAlumno, $idMateria);
    $stmtNota->execute();
    $resultNota = $stmtNota->get_result();
    if ($resultNota->num_rows > 0) {
        $rowNota = $resultNota->fetch_assoc();
        return $rowNota[$tipoParcial];
    } else {
        return "";
    }
}

include("include/header.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Alumnos</title>
    <style>
        h1 {
            margin: 20px;
        }
        form {
            margin: 10px;
        }
        table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 80%;
        }
        th, td {
            padding: 8px;
            border: 1px solid #dddddd;
            text-align: left;
        }
    </style>
</head>
<center>
<body>

<h1>Lista de Alumnos</h1>

<form action="" method="post">
    <label for="materia">Seleccione la materia:</label>
    <select name="materia" id="materia">
        <?php while ($rowMateria = $resultMaterias->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($rowMateria['id_materia']) ?>">
                <?= htmlspecialchars($rowMateria['nombre_materia']) ?>
            </option>
        <?php endwhile; ?>
    </select>
    <input type="submit" name="seleccionar_materia" value="Seleccionar">
</form>

<?php
// Si se ha seleccionado una materia, mostrar la lista de alumnos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['seleccionar_materia'])) {
    $materiaSeleccionada = $_POST['materia'];
    $sqlAlumnos = "SELECT u.id_usuario as id_alumno, u.nombre, u.apellido, u.dni 
                   FROM usuarios u
                   INNER JOIN alumnos_materias am ON u.id_usuario = am.id_alumno 
                   WHERE am.id_materia = ?";
    $stmtAlumnos = $conn->prepare($sqlAlumnos);
    $stmtAlumnos->bind_param("i", $materiaSeleccionada);
    $stmtAlumnos->execute();
    $resultAlumnos = $stmtAlumnos->get_result();

    if ($resultAlumnos->num_rows > 0) {
        ?>
        <form method="POST" action="">
            <input type="hidden" name="materia" value="<?= htmlspecialchars($materiaSeleccionada) ?>">
            <table>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>DNI</th>
                    <th>Parcial 1</th>
                    <th>Parcial 2</th>
                    <th>Final</th>
                </tr>
                <?php while ($rowAlumno = $resultAlumnos->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($rowAlumno['nombre']) ?></td>
                        <td><?= htmlspecialchars($rowAlumno['apellido']) ?></td>
                        <td><?= htmlspecialchars($rowAlumno['dni']) ?></td>
                        <td><?= obtenerNota($conn, $rowAlumno['id_alumno'], $materiaSeleccionada, 'parcial1') ?></td>
                        <td><?= obtenerNota($conn, $rowAlumno['id_alumno'], $materiaSeleccionada, 'parcial2') ?></td>
                        <?php
                        $nota_parcial1 = obtenerNota($conn, $rowAlumno['id_alumno'], $materiaSeleccionada, 'parcial1');
                        $nota_parcial2 = obtenerNota($conn, $rowAlumno['id_alumno'], $materiaSeleccionada, 'parcial2');
                        if ($nota_parcial1 >= 4 && $nota_parcial2 >= 4) {
                            ?>
                            <td><input type="number" name="notas[<?= $rowAlumno['id_alumno'] ?>][nota3]"
                                       value="<?= obtenerNota($conn, $rowAlumno['id_alumno'], $materiaSeleccionada, 'final') ?>" min="0" max="10"></td>
                            <?php
                        } else {
                            echo "<td>Los parciales no están aprobados</td>";
                        }
                        ?>
                    </tr>
                <?php endwhile; ?>
            </table>
         
                <input type="submit" name="guardar" value="Guardar Cambios">
            
        </form>
        <?php
    } else {
        echo "No hay alumnos inscritos en esta materia.";
    }
}

include("include/footer.php");
?>

<br><a href="profesor.php"><button class="botones">Página Principal</button></a></center>

</body>
</html>