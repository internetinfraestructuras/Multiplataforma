<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$troncal=$_POST['form_troncal'];
$callcenter=$_POST['form_callcenter'];
$nuevo_numerico=$_POST['form_numerico'];
$posicion=$_POST['form_posicion'];


//$cif = cif_superusuario($usuario);	

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="ClanTemplates" />
	<meta name="keywords" content="Clan" />
	<meta name="description" content="Grupo REQ Plataforma Prepago" />
	<meta name="robots" content="all" />
	<title>Plataforma Prepago Grupo REQ</title>

	<style type="text/css" title="currentStyle" media="screen">
		 @import "style/stylesheet.css";
	</style>

	<style type="text/css" title="currentStyle" media="screen">
		 @import "style/req.css";
	</style>

</head>

<body>

<div id="container">
	<div id="banner">
	    <?php mostrar_datos_user($_SESSION['nombre_usuario']) ?>
	    <h1 id="logo"><a href="#" title="Plataforma Prepago">Plataforma Prepago</a></h1>
	</div>
	
	<ul id="navigation">

	</ul>
	<!-- contenedor principal donde desplegar informacion -->
	<div id="main">
		<div id="content">
			
			         
					<?php
					 
						//conectamos a  MYSQL

					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
					
					$errorcito="";

					$sel="insert into lista_numeros_callcenter(usuario_troncal,posicion,numero) VALUES ('$troncal','$posicion','$nuevo_numerico')";
					$query = mysql_query($sel) or die(mysql_error());

		
					mysql_close($db);	

					?>
					
					<h4 class="title">A�adiendo num�rico</h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
					<?php
					
					if($errorcito=="")
					{
					echo"
					
					Insertado con exito.<br><br><br>
					<form type=\"GET\" action=\"/lista_salida_numericos_troncal.php?callcenter=$callcenter&usuario_troncal=$troncal\">
					<p>&nbsp;</p>
					<input type=\"button\" value=\"Volver \"  onClick=\"location.href = 'lista_salida_numericos_troncal.php?callcenter=$callcenter&usuario_troncal=$troncal'\"  style=\" width:90;height:90; background-image:url(./images/bg_button.gif)\"> 
					</form>";
					}
					else
					{
					echo"
					Error a�adir.<br><br><br>
					$errorcito<br>
					<form type=\"GET\" action=\"/lista_salida_numericos_troncal.php?callcenter=$callcenter&usuario_troncal=$troncal\">
					<p>&nbsp;</p>
					<input type=\"button\" value=\"Volver \"  onClick=\"location.href = 'lista_salida_numericos_troncal.php?callcenter=$callcenter&usuario_troncal=$troncal'\"  style=\" width:90;height:90; background-image:url(./images/bg_button.gif)\"> 
					</form>";
					}
					?>
					</div>
					<p align="center">&nbsp;</p>
					
	             <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
	
		</div>
		<div id="left-sidebar">
				<div class="block recent-matches">
					<?php  menu_principal(); ?>
				</div>

				<div class="block sponsors">
					<?php bloque_sponsor(); ?>
				</div>
			
				<div class="block recent-threads">
					<?php otros_servicios(); ?>
				</div>
		</div>
	</div>
	<!-- el footer de todalavida-->
		<div id="footer">
		<?php pie(); ?>
	</div>

</body>
</html>