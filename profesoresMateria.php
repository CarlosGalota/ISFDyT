<?php
session_start();
if(isset($_SESSION['id_usuario']) && $_SESSION['rol']==1){

}
else{
    echo "ACCESSO NO AUTORIZADO";
    exit();
}


?>
<?php include ("include/header.php") ?>


    <title>Lista de Materias</title>
</head>
<body>

<h1>Selecciona una materia:</h1>
    <form action="calificacionesProf.php" method="post">
        <select name="materia">
            <?php
            require "conexion.php";
            $conn=conectar();
            $materias = "select * from pro_materia where pro_id=".$_SESSION['']."";
            foreach ($materias as $materia) {
                echo '<option value="' . $materia['id'] . '">' . $materia['nombre'] . '</option>';
            }
            ?>
        </select>
        <input type="submit" value="Mostrar Alumnos">
    </form>









<?php include ("include/footer.php") ?>