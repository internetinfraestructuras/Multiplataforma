<?php

include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$descripcion=$_POST['form_descripcion'];
$troncal=$_POST['form_id_troncal'];
$callcenter=$_POST['form_callcenter'];


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
					  
						//enviamos un mail al administrador con la peticion de asignacion
					
					$user="admin";
					$asunto="Solicitud numerico de entrada";
					$texto="Creacion de numerico para superusuario: $usuario en callcenter:$callcenter en troncal:$troncal del siguiente tipo:$descripcion";
					
					enviar_mail_usuario($user,$asunto,$texto);		
					
					?>
					
					<h4 class="title">Solicitud numérico <?php echo $form_cif;?></h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
					
					Solicitud enviada con éxito.<br><br><br>
					<p>
					Recibirá una notificación via email cuando la asignación esté realizada.
					</p>
					<p>
					&nbsp;
					</p>
					<form type="GET" action="troncales_callcenter.php?callcenter=<?php echo $callcenter; ?>">
					<input type="button" value="Volver a Troncales"  onClick="location.href = 'troncales_callcenter.php?callcenter=<?php echo $callcenter; ?>'"  style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 
					</form>
					
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