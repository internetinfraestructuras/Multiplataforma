<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$form_cif=$_POST['form_cif'];
$form_nombre=$_POST['form_nombre'];
$form_email=$_POST['form_email'];
$form_direccion=$_POST['form_direccion'];
$form_usuario=$_POST['form_usuario'];
$form_password=$_POST['form_password'];
$form_umbralalerta=$_POST['form_umbralalerta'];
$form_tipodefacturacion=$_POST['form_tipodefacturacion'];
$form_grupoderecarga=$_POST['form_grupoderecarga'];


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
	
	<!--link rel="Shortcut Icon" type="image/x-icon" href="images/favicon.ico" /-->	
	<script language="javascript" type="text/javascript" src="datetimepicker.js">
    </script>

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
						
						
					//menos el cif puedo modificar todo
					
					$sel = "update usuarios set nombre = '$form_nombre', email = '$form_email', direccion = '$form_direccion', usuario='$form_usuario', password='$form_password', umbralalerta='$form_umbralalerta', tipofacturacion='$form_tipodefacturacion',grupoderecarga='$form_grupoderecarga' where cif='$form_cif'";
				
					$query = mysql_query($sel) or die(mysql_error());
						
					mysql_close($db);		
						
						

					?>
					
					<h4 class="title">Modificado <?php echo $form_cif;?></h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
					
					Cliente modificado correctamente.<br><br><br>
					<form action="clientes.php">
					<input type="submit" value="Volver a Clientes" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 
					
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