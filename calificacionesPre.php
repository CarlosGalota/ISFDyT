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


$sql = "SELECT 
            *
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
  if(mysqli_affected_rows($conn) > 0){


    while($registro=mysqli_fetch_assoc($result)){
    ?>
        <tr>
        <td><?php echo $registro['nombre']?></td>
        <td><?php echo $registro['apellido']?></td>
        <td><?php echo $registro['dni']?></td>
        <td><?php  
                $matnom="select nombreMaterias from materias where idMaterias=".$registro['idMaterias']. ";";
    
                $conmat=mysqli_query($conn,$matnom);
                
                $resmat=mysqli_fetch_assoc($conmat);
        
        echo $resmat['nombreMaterias']?></td>
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
