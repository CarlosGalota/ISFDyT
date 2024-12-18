<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 2) {
    echo "ACCESO NO AUTORIZADO";
    exit();
}
?>

<?php include("include/header.php") ?>

<title>Lista de Alumnos</title>

<div class="content">
    <form action="mostrarMaterias.php" method="get">
        <table>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>DNI</th>
                <th>Seleccionar</th>
            </tr>
            <?php

            require "conexion.php";
            $sql = "SELECT DISTINCT
                     u.idUsuarios, u.nombre, u.apellido, u.dni
                FROM 
            usuarios AS u
                INNER JOIN 
            notas AS n ON u.idUsuarios = n.idUsuarios
                INNER JOIN 
            usuarios_carreras AS uc ON uc.idUsuarios = ".$_SESSION['id_usuario']."
             WHERE 
            u.idRoles = 3;
             ";

            $conn = conectar();

            if ($stmt = $conn->prepare($sql)) {
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($registro = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($registro['nombre']) ?></td>
                            <td><?= htmlspecialchars($registro['apellido']) ?></td>
                            <td><?= htmlspecialchars($registro['dni']) ?></td>
                            <td>
                                <input type="radio" name="idAlumno" value="<?= htmlspecialchars($registro['idUsuarios']) ?>" required>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="4"><h3>No hay alumnos registrados</h3></td>
                    </tr>
                    <?php
                }

                $stmt->close();
            }

            $conn->close();
            ?>
        </table>
        <div class="button">
            <input type="submit" value="Ver Materias" class="botones">
        </div>
    </form>
    <div class="button">
        <a href="preceptor.php"><button class="botones">Inicio</button></a>
    </div>
</div>

<?php include("include/footer.php") ?>
