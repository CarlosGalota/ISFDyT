<?php
session_start();
require "conexion.php";

// Consultar las carreras
$conn = conectar();
$carreras_sql = "SELECT idCarreras, nombreCarreras FROM carreras";
$carreras_result = mysqli_query($conn, $carreras_sql);

// Consultar las materias
$materias_sql = "SELECT idMaterias, nombreMaterias, idCarreras FROM Materias";
$materias_result = mysqli_query($conn, $materias_sql);
$materias = [];
while ($materia = mysqli_fetch_assoc($materias_result)) {
    $materias[$materia['idCarreras']][] = $materia;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar datos</title>
    <link rel="stylesheet" href="estilos.css">
    <script>
        function toggleMateriaCarrera() {
            var rol = document.getElementById("rol").value;
            var carreraField = document.getElementById("carreraField");
            var materiaField = document.getElementById("materiaField");

            if (rol == "1") { // Profesor
                carreraField.style.display = "block";
                materiaField.style.display = "block";
            } else if (rol == "2" || rol == "3") { // Preceptor o Alumno
                carreraField.style.display = "block";
                materiaField.style.display = "none";
            } else {
                carreraField.style.display = "none";
                materiaField.style.display = "none";
            }
        }

        function updateMaterias() {
            var carreraSelect = document.getElementById("carrera");
            var materiaSelect = document.getElementById("materia");
            var selectedCarrera = carreraSelect.value;
            var materias = <?php echo json_encode($materias); ?>;
            
            // Limpiar materias
            materiaSelect.innerHTML = "";

            // Agregar nuevas materias
            if (materias[selectedCarrera]) {
                materias[selectedCarrera].forEach(function(materia) {
                    var option = document.createElement("option");
                    option.value = materia.idMaterias;
                    option.text = materia.nombreMaterias;
                    materiaSelect.add(option);
                });
            }
        }
    </script>
</head>
<body>
<header>
    <div class="logo">
        <img src="logo.png" alt="">
        <h2 class="titulo">INGRESAR DATOS</h2>
    </div>
</header>

<form action="GuardarUsu.php" method="post">
    <div class="usuarios">
        <table>
            <tr>
                <td colspan="3"><h3 class="titulo">TABLA USUARIO</h3></td>
            </tr>
            <tr>
                <td><label>Nombre</label></td>
                <td><input type="text" maxlength="50" name="nombre" required></td>
            </tr>
            <tr>
                <td><label>Apellido</label></td>
                <td><input type="text" maxlength="50" name="apellido" required></td>
            </tr>
            <tr>
                <td><label>Usuario</label></td>
                <td><input type="text" maxlength="100" name="usuario" required></td>
            </tr>
            <tr>
                <td><label>DNI</label></td>
                <td><input type="number" maxlength="8" name="dni" required></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" maxlength="255" name="pass" required></td>
            </tr>
            <tr>
                <td>Rol</td>
                <td>
                    <select id="rol" name="rol" onchange="toggleMateriaCarrera()" required>
                        <option value="3">Preceptor</option>
                        <option value="2">Profesor</option>
                        <option value="1">Alumno</option>
                    </select>
                </td>
            </tr>
            <tr id="carreraField" style="display:none;">
                <td><label>Carrera</label></td>
                <td>
                    <select id="carrera" name="carrera" onchange="updateMaterias()" required>
                        <option value="">Seleccione una carrera</option>
                        <?php while ($carrera = mysqli_fetch_assoc($carreras_result)) { ?>
                            <option value="<?php echo $carrera['idCarreras']; ?>"><?php echo $carrera['nombreCarreras']; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr id="materiaField" style="display:none;">
                <td><label>Materia</label></td>
                <td>
                    <select id="materia" name="materia">
                        <!-- Opciones se llenarán con JavaScript -->
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2"><input class="submit" type="submit" value="Cargar Usuario"></td>
            </tr>
        </table>
    </div>
</form>

<h1><center>
<?php
if(isset($_GET['ok'])){
    echo "Exito!";
}
if(isset($_GET['error'])){
    echo "No se pudo cargar el usuario";
}
?>
</h1></center>

<div class="usuarios">
    <table border="1" cellspacing="0" bordercolor="orange">
        <tr>
            <th>ID usuario</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Rol</th>
           
        </tr>
        <?php
        $sql = "SELECT *
                FROM usuarios;";
        $resulset = mysqli_query($conn, $sql);
        while ($registro = mysqli_fetch_assoc($resulset)) {
        ?>
        <tr>
            <td align="center"><?php echo $registro['idUsuarios']; ?></td>
            <td><?php echo $registro['nombre']; ?></td>
            <td><?php echo $registro['apellido']; ?></td>
            <td><?php echo $registro['idRoles']; ?></td>
        </tr>
        <?php
        }
        ?>
    </table>
</div>
<center>
    <nav>
        <a href="../index.php" class="nav-link"><button>Inicio</button></a>
    </nav>
</body>
</html>
