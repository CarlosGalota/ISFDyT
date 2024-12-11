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

<div class="content">
	<form method=POST action='buscarAlumno.php'>
        <p><h2>Bienvenido al Sistema de Calificaciones</h2></p>
		<input type=text name=apellido maxlength=20 placeholder="apellido" required><button class="botones" type="submit">Buscar alumno</button> <br><br>
    </form>

		<a href="calificacionesPre.php"><button class="botones">Alumnos</button></a>
        <a href="preProfesores.php"><button class="botones">Profesor</button></a> 
		</div>
       
       
 <?php include ("include/footer.php") ?>
