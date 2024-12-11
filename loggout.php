<?php
session_start();

//session_start();

session_destroy();
    ?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="css/style.css">
		<title>ISFDyT n24</title>
	</head>
	<body>
		<div class="content">
		<form action=index.php>
			<?php echo "Hasta pronto " .$_SESSION['nombre']; ?>
			<br>
		<td><input class="butones" type="submit" value="Volver a iniciar sesión"></td>
		</div>
	
	</body>
	</html>
  