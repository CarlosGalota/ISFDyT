<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 1) {
    echo "ACCESO NO AUTORIZADO";
    exit();
}
?>
<?php include("include/header.php") ?>
<title>Profesores</title>

<div class="content">
    <h2>Bienvenido al Sistema de Calificaciones</h2>
    <form action="buscarAlumno.php" method="post">
        <input type="text" name="apellido" maxlength="20" placeholder="Apellido" required>
        <button class="botones" type="submit">Buscar alumno</button>
    </form>
    <br><br>
    <form>
        <button class="botones" type="submit" formaction="calificacionProf.php" formmethod="post">Cargar Parciales</button>
        <button class="botones" type="submit" formaction="final.php" formmethod="post">Cargar Final</button>
    </form>
</div>

<?php include("include/footer.php") ?>
