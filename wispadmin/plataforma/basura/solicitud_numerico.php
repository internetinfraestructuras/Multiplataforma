<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$troncal=$_POST['usuario_troncal'];
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
			
			         
					
					<h4 class="title">Solicitud nuevo numérico de entrada : <?php echo $troncal;?></h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
					</div>
					
					<form action="solicitud_numerico_efectivo.php" method="post" name="form_cliente" width="30%">
					<div align="center">
					
					</p>
					<p>

					<table  border="1" cellpadding="0" cellspacing="0">
					<caption>Descripción numérico deseado</caption>
					<tr>
					<td width="20%">Descripción: </td>
					<td width="30%" align="center"><input type="text" align="center" size="53" name="form_descripcion" value=""></td>
					<input type="hidden" align="center" size="53" name="form_id_troncal" value="<?php echo $troncal;?>">
					<input type="hidden" align="center" size="53" name="form_callcenter" value="<?php echo $callcenter;?>">
					</tr>
					</table> 					
					
					</div>
					<p align="center">&nbsp;</p>
					<div align="center">
					
					<input type="submit" value="Solicitar número" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	&nbsp;&nbsp;&nbsp;

					</div>
					</form>

					


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