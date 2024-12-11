<?php
session_start();
if(isset($_SESSION['id_usuario'])&& $_SESSION['rol']==3){

}
else{
    echo "ACCESSO NO AUTORIZADO";
    exit();
}

?>

<?php include ("include/header.php") ?>


	<div class="containerInicio">
	<form>
        <p><h2>Bienvenido al Sistema de Calificaciones</h2></p>
        <button class="botones" type="submit" formaction="aluCalificaciones.php" formmethod="post">Ver todas las calificaciones</button>
        <button class="botones" type="submit" formaction="altaMateria.php" formmethod="post">Inscribirse Materias</button>
    </form>
	</div>

    <?php include ("include/footer.php") ?>