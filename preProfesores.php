<?php
session_start();
if(isset($_SESSION['id_usuario']) && $_SESSION['rol']==2){

}
else{
    echo "ACCESSO NO AUTORIZADO";
    exit();
}
?>
<?php include ("include/header.php") ?>
<?php include ("include/footer.php") ?>

    <title>Lista de Profesores</title>
</head>
<body>
    
<div class="content">
<form action="nuevaCalificacion.php" method="post">
<table>

<tr>
        
        <th>Nombre</th>
        <th>Apellido</th>
        <th>DNI</th>
        <th>Materia</th>
        
    </tr>
<?php

require "conexion.php";

$conn=conectar();

$sql = "SELECT * FROM usuarios JOIN profesores_materias ON profesores_materias.id_profesor = usuarios.id_usuario
        WHERE usuarios.rol = 1 AND usuarios.id_carrera = ?;";

$conn = conectar();

if ($stmt = $conn->prepare($sql)) {
  $stmt->bind_param("i", $_SESSION['carrera_id']); // Enlaza la variable de sesión como un entero
  $stmt->execute();
  $result = $stmt->get_result();
  while($registro=mysqli_fetch_assoc($result)){
    ?>
        <tr>
        <td><?php echo $registro['nombre']?></td>
        <td><?php echo $registro['apellido']?></td>
        <td><?php echo$registro['dni']?></td>
        <td><?php  
                $matnom="select nombre_materia from materias where id_materia=".$registro['id_materia']. ";";
    
                $conmat=mysqli_query($conn,$matnom);
                
                $resmat=mysqli_fetch_assoc($conmat);
        
        echo $resmat['nombre_materia']?></td>
        </tr>
    <?php
    }
    
    ?>
    </table>
    </form>
<?php
  $stmt->close();
} else {
  echo "Error: " . $conn->error;
}
?>

<div class="button">
<a href="preceptor.php"><button class="botones">Inicio</button></a>
</div>
</div>

