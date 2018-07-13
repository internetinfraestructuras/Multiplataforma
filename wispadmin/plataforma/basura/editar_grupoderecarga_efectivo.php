<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$form_nombre=$_POST['form_nombre'];
$form_nombre_antiguo=$_POST['form_nombre_antiguo'];
$form_importe=$_POST['form_importe'];
$form_acumulable=$_POST['form_acumulable'];
$color=$_POST['background-color'];



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
					  $cif_super=cif_superusuario($usuario);
						//conectamos a  MYSQL

					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
						
						
					
					
					$sel = "update 
					gruposderecarga set nombregrupo = '$form_nombre',
					importerecarga = '$form_importe',
					acumulable = '$form_acumulable', color='$color'
					where cif_super='$cif_super' and nombregrupo='$form_nombre_antiguo'";

					$query = mysql_query($sel) or die(mysql_error());
						
					//ojoooooooo si cambia el nombre lo tengo que cambiar en todos los
					//clientes que tenian este como grupo de recargas
					if($form_nombre != $form_nombre_antiguo)
					{
						$sel="update usuarios set grupoderecarga='$form_nombre' where
							  grupoderecarga='$form_nombre_antiguo' and cif_super='$cif_super'";
						
						$query = mysql_query($sel) or die(mysql_error());
					
					}
						
						
					mysql_close($db);		
						
						

					?>
					
					<h4 class="title">Modificado <?php echo $form_nombre;?></h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
					
					Grupo Recarga modificado correctamente.<br><br><br>
					<form action="gruposderecarga.php">
					<input type="submit" value="Volver a Grupos de Recarga" style=" width:90;height:20; background-image:url(./images/bg_button.gif)"> 
					
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