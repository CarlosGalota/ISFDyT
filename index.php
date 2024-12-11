<?php
session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

</script><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>.:: Instituto Superior de Formación Docente y Técnica Nº 24 ::.</title>

</head>

<body>
<aside>
    <a href=admin/admin.php>admin</a>
    </aside>
<div id="Contenedor" align="center" class="Contenedor">
	<div id="Cabecera" class="Cabecera" align="center">
    	<table width="100%" height="90" align="center" border="0">
          <tbody><tr>
	<td align="center" valign="bottom" colspan="3"><strong class="Form_titulo">"Instituto Superior de Formación Docente y Técnica N24"</strong></td>
          </tr>
          <tr>
    <td width="48%" align="right" valign="middle" class="Form_subtitulo"><strong>&nbsp;</strong></td>
          <td width="10%" align="center" valign="middle">&nbsp;</td>
          </tr>
        </tbody></table>
	</div>
    <hr> 
   
	<div id="Cuerpo" align="center" class="Cuerpo">
<form action=login.php method=post>
	<table>
    <tr><h2>Login</h2></tr>
    <tr><td>Usuario:</td> <td><input type=text name=usuario placeholder="Usuario" maxlength=100 required><br></td></tr>
    <tr><td>Clave:</td><td><input type=password name=pass maxlength=255 placeholder="contraseña" required><br></td></tr>
    <tr><td colspan="2"><br><br><center><input class="" type="submit" value="Ingresar"></center></td></tr> <br>
	<table>
  <?php
        if(isset($_SESSION['error'])){
            echo $_SESSION['error'];
        }
    ?>
<form>
	</div><br>
</div>

</body></html>