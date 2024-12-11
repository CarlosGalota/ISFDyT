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
    <title>Alta Materia</title>
    <style>
        table {
            margin: 0 auto; /* Centrar la tabla horizontalmente */
            border-collapse: collapse; /* Colapsar los bordes de la tabla */
            width: 80%; /* Establecer el ancho de la tabla */
        }
        th, td {
            padding: 8px; /* Agregar relleno a las celdas */
            border: 1px solid #dddddd; /* Agregar bordes a las celdas */
            text-align: left; /* Alinear el texto a la izquierda */
        }
    </style>
</head>
<body>
    <div class="content">
        <h1>Inscribirse a materia</h1>
        <h3>Alta Materia</h3>
        <form method="post">
            <select name="materias">
            <?php 
                $conn = conectar();
                $sql = "SELECT * FROM materias WHERE id_carrera = " . $_SESSION['carrera_id'];
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
            ?>
                    <option value="<?= htmlspecialchars($row['id_materia']); ?>"><?= htmlspecialchars($row['nombre_materia']); ?></option>
            <?php
                    }
                } else {
                    echo "<option value=''>No hay materias disponibles</option>";
                }
                $conn->close();
            ?>
            </select>
            <button type="submit" class="botones">Submit</button>
        </form>
        
        <h3>Materias Registradas</h3>
        <table>
            <tr>
                <th>Nombre de la Materia</th>
                <th>Acción</th>
            </tr>
            <?php 
                $id_alumno = $_SESSION['id_usuario'];
                $sql = "SELECT materias.nombre_materia, materias.id_materia
                        FROM alumnos_materias 
                        INNER JOIN materias ON alumnos_materias.id_materia = materias.id_materia
                        WHERE alumnos_materias.id_alumno = $id_alumno";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
            ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nombre_materia']); ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="baja_materia" value="<?= htmlspecialchars($row['id_materia']); ?>">
                                    <input type="submit" value="Darse de Baja" class="botones">
                                </form>
                            </td>
                        </tr>
            <?php
                    }
                } else {
                    echo "<tr><td colspan='2'>No estás registrado en ninguna materia.</td></tr>";
                }
                $conn->close();
            ?>
        </table>
        <br>
        <center><a href="alumno.php"><button class="botones">Volver</button></a></center>
    </div>

<?php include("include/footer.php") ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se ha seleccionado una materia para alta
    if (isset($_POST['materias'])) {
        $conn = conectar();
        $id_materia = $_POST['materias'];
        $id_alumno = $_SESSION['id_usuario'];
        $sql_check = "SELECT * FROM alumnos_materias WHERE id_alumno = $id_alumno AND id_materia = $id_materia";
        $result_check = $conn->query($sql_check);
        
        if ($result_check->num_rows == 0) {
            
            $sql_insert = "INSERT INTO alumnos_materias (id_alumno, id_materia) VALUES ($id_alumno, $id_materia)";
            
            if ($conn->query($sql_insert) === TRUE) {
                echo "Materia registrada correctamente";
            } else {
                echo "Error al registrar la materia: " . $conn->error;
            }
        } else {
            echo "Ya estás registrado en esta materia";
        }
        
        $conn->close();
    } elseif (isset($_POST['baja_materia'])) {
        // Verificar si se ha seleccionado una materia para darse de baja
        $id_materia_baja = $_POST['baja_materia'];
        $id_alumno = $_SESSION['id_usuario'];

        // Eliminar la materia seleccionada de la tabla alumnos_materias
        $conn = conectar();
        $sql = "DELETE FROM alumnos_materias WHERE id_alumno = $id_alumno AND id_materia = $id_materia_baja";
        
        if ($conn->query($sql) === TRUE) {
            echo "Te has dado de baja de la materia correctamente";
        } else {
            echo "Error al darse de baja de la materia: " . $conn->error;
        }

        $conn->close();
    } else {
        echo "Por favor, selecciona una materia o acción válida";
    }

    // Redireccionar después del envío del formulario para evitar reenvíos duplicados
    echo "<script>window.location.href = window.location.href;</script>";
    exit;
}
?>
