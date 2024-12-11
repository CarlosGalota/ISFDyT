<?php
session_start();
if(isset($_SESSION['id_usuario']) && $_SESSION['rol']==1){

}
else{
    echo "ACCESSO NO AUTORIZADO";
    exit();
}
require "conexion.php";

?>
<?php include ("include/header.php"); ?>
<?php include ("include/footer.php");
?>

<?php

// Verificar si el usuario ha iniciado sesión como profesor
if (!isset($_SESSION['id_usuario'])) {
  header('Location: logging.php');
  exit;
}

$conn = conectar();
$idProfesor = $_SESSION['id_usuario'];

// Obtener la lista de materias del profesor
$sqlMaterias = "SELECT m.id_materia, m.nombre_materia 
              FROM profesores_materias pm
              JOIN materias m ON pm.id_materia = m.id_materia
              WHERE pm.id_profesor = ?";
$stmtMaterias = $conn->prepare($sqlMaterias);
$stmtMaterias->bind_param("i", $idProfesor);
$stmtMaterias->execute();
$resultMaterias = $stmtMaterias->get_result();

$errorMessage = '';

// Procesar el formulario si se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guardar'])) {
  $materia = $_POST['materia'];
  
  $stmtCheck = $conn->prepare("SELECT id_nota FROM notas WHERE id_alumno = ? AND id_materia = ? AND id_profesor = ?");
  $stmtInsert = $conn->prepare("INSERT INTO notas (id_profesor, id_alumno, parcial1, parcial2, id_materia) VALUES (?, ?, ?, ?, ?)");
  $stmtUpdate = $conn->prepare("UPDATE notas SET parcial1 = ?, parcial2 = ? WHERE id_alumno = ? AND id_materia = ? AND id_profesor = ?");
  
  $valid = true;
  foreach ($_POST['notas'] as $idAlumno => $notas) {
      $parcial1 = $notas['nota1'] ?? null;
      $parcial2 = $notas['nota2'] ?? null;

      // Validar las notas
      if (($parcial1 !== null && ($parcial1 < 0 || $parcial1 > 10)) || ($parcial2 !== null && ($parcial2 < 0 || $parcial2 > 10))) {
          $valid = false;
          $errorMessage = 'Las notas deben estar entre 0 y 10.';
          break;
      }

      if ($valid) {
          // Verificar si la nota ya existe
          $stmtCheck->bind_param("iii", $idAlumno, $materia, $idProfesor);
          $stmtCheck->execute();
          $stmtCheck->store_result();

          if ($stmtCheck->num_rows > 0) {
              // Actualizar si ya existe
              $stmtUpdate->bind_param("iiiii", $parcial1, $parcial2, $idAlumno, $materia, $idProfesor);
              $stmtUpdate->execute();
          } else {
              // Insertar si no existe
              $stmtInsert->bind_param("iiiii", $idProfesor, $idAlumno, $parcial1, $parcial2, $materia);
              $stmtInsert->execute();
          }
      }
  }

  if ($valid) {
      echo '<script>alert("Notas actualizadas correctamente.")</script>';
  } else {
      echo '<script>alert("' . $errorMessage . '")</script>';
  }
}

// Función para obtener la nota de un alumno en una materia específica
function obtenerNota($conn, $idAlumno, $idMateria, $tipoParcial) {
  $sqlNota = "SELECT $tipoParcial FROM notas WHERE id_alumno = ? AND id_materia = ?";
  $stmtNota = $conn->prepare($sqlNota);
  $stmtNota->bind_param("ii", $idAlumno, $idMateria);
  $stmtNota->execute();
  $resultNota = $stmtNota->get_result();
  if ($resultNota->num_rows > 0) {
      $rowNota = $resultNota->fetch_assoc();
      return $rowNota[$tipoParcial];
  } else {
      return "";
  }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Lista de Alumnos</title>
  <script>
      function validarFormulario() {
          const inputs = document.querySelectorAll('input[type="number"]');
          for (let input of inputs) {
              if (input.value < 0 || input.value > 10) {
                  alert('Las notas deben estar entre 0 y 10.');
                  return false;
              }
          }
          return true;
      }
  </script>
</head>
<center>
<body>

<h1>Lista de Alumnos</h1>

<form action="" method="post">
  <label for="materia">Seleccione la materia:</label>
  <select name="materia" id="materia">
      <?php while ($rowMateria = $resultMaterias->fetch_assoc()): ?>
          <option value="<?= htmlspecialchars($rowMateria['id_materia']) ?>">
              <?= htmlspecialchars($rowMateria['nombre_materia']) ?>
          </option>
      <?php endwhile; ?>
  </select>
  <input type="submit" name="seleccionar_materia" value="Seleccionar">
</form>

<?php
// Si se ha seleccionado una materia, mostrar la lista de alumnos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['seleccionar_materia'])) {
  $materiaSeleccionada = $_POST['materia'];
  $sqlAlumnos = "SELECT u.id_usuario as id_alumno, u.nombre, u.apellido, u.dni 
                 FROM usuarios u
                 INNER JOIN alumnos_materias am ON u.id_usuario = am.id_alumno 
                 WHERE am.id_materia = ?";
  $stmtAlumnos = $conn->prepare($sqlAlumnos);
  $stmtAlumnos->bind_param("i", $materiaSeleccionada);
  $stmtAlumnos->execute();
  $resultAlumnos = $stmtAlumnos->get_result();

  if ($resultAlumnos->num_rows > 0) {
      ?>
      <form method="POST" action="" onsubmit="return validarFormulario()">
          <input type="hidden" name="materia" value="<?= htmlspecialchars($materiaSeleccionada) ?>">
          <table>
              <tr>
                  <th>Nombre</th>
                  <th>Apellido</th>
                  <th>DNI</th>
                  <th>Parcial 1</th>
                  <th>Parcial 2</th>
              </tr>
              <?php while ($rowAlumno = $resultAlumnos->fetch_assoc()): ?>
                  <tr>
                      <td><?= htmlspecialchars($rowAlumno['nombre']) ?></td>
                      <td><?= htmlspecialchars($rowAlumno['apellido']) ?></td>
                      <td><?= htmlspecialchars($rowAlumno['dni']) ?></td>
                      <td>
                          <input type="number" name="notas[<?= $rowAlumno['id_alumno'] ?>][nota1]" 
                                 value="<?= obtenerNota($conn, $rowAlumno['id_alumno'], $materiaSeleccionada, 'parcial1') ?>" min="0" max="10">
                      </td>
                      <td>
                          <input type="number" name="notas[<?= $rowAlumno['id_alumno'] ?>][nota2]" 
                                 value="<?= obtenerNota($conn, $rowAlumno['id_alumno'], $materiaSeleccionada, 'parcial2') ?>" min="0" max="10">
                      </td>
                  </tr>
              <?php endwhile; ?>
          </table>
          
              <input type="submit" name="guardar" value="Guardar Cambios">
          
      </form>
      <?php
  } else {
      echo "No hay alumnos inscritos en esta materia.";
  }
}
?>

<br>
<a href="profesor.php"><button class="botones">Página Principal</button></a></center>

</body>
</html>
