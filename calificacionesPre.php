<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 2) {
    echo "ACCESO NO AUTORIZADO";
    exit();
}
?>

<?php include ("include/header.php") ?>

    <title>Lista de Calificaciones</title>

<div class="content">
<form action="nuevaCalificacion.php" method="post">
<table >

<tr>
        
        <th>Nombre</th>
        <th>Apellido</th>
        <th>DNI</th>
        <th>Materia</th>
        <th>Parcial 1º</th>
        <th>Parcial 2º</th>
        <th>Final</th>
        
    </tr>
<?php

require "conexion.php";


$sql = "SELECT * FROM usuarios JOIN notas ON notas.id_alumno = usuarios.id_usuario
        WHERE usuarios.rol = 3 AND usuarios.id_carrera = ?";

$conn = conectar();

if ($stmt = $conn->prepare($sql)) {
  $stmt->bind_param("i", $_SESSION['carrera_id']); // Enlaza la variable de sesión como un entero
  $stmt->execute();
  $result = $stmt->get_result();
  if(mysqli_affected_rows($conn) > 0){


    while($registro=mysqli_fetch_assoc($result)){
    ?>
        <tr>
        <td><?php echo $registro['nombre']?></td>
        <td><?php echo $registro['apellido']?></td>
        <td><?php echo $registro['dni']?></td>
        <td><?php  
                $matnom="select nombre_materia from materias where id_materia=".$registro['id_materia']. ";";
    
                $conmat=mysqli_query($conn,$matnom);
                
                $resmat=mysqli_fetch_assoc($conmat);
        
        echo $resmat['nombre_materia']?></td>
        <td><?php echo $registro['parcial1']?></td>
        <td><?php echo $registro['parcial2']?></td>
        <td><?php echo $registro['final']?></td>
        </tr>
    <?php
    }
    } else {
        ?>
        <tr>
            <td><h3>No hay alumnos registrados</h3></td>
        </tr>
        <?php
    }
    
    $conn->close();
}
    ?>


</table>
</form>
<div class="button">
    <a href="preceptor.php"><button class="botones">Inicio</button></a>
</div>

</div>




<?php include ("include/footer.php") ?>
