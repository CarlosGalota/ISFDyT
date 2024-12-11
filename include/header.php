<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>ISFDyT N24</title>
</head>
<body>
    <header class="header">
        <a href="index.php">
            <img class="header__logo" src="img/logo-chico.png" alt="Logotipo">
        </a>
    </header>
    <div class="main-container">
        <aside>
            <form class="sesion" action="loggout.php">
                <?php
                echo "Sesión iniciada como " . $_SESSION['nombre'] . "<br>";
                echo "Hola, " . $_SESSION['nombre'] . "<br>";
                ?>
                <input type="submit" value="Cerrar sesión">
            </form>
        </aside>
