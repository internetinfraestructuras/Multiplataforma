<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$cif_cliente=$_POST['cif_cliente'];

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
			
			         
					
					<h4 class="title">Alta Call Center Cliente: <?php echo $cif_cliente;?></h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
					</div>
					
					<form action="alta_callcenter_efectivo.php" method="post" name="form_cliente" width="30%">
					<div align="center">
					
					</p>
					<p>

					<table  border="1" cellpadding="0" cellspacing="0">
					<caption>Datos Callcenter</caption>
					<tr>
					<td width="20%">Nombre Call Center: </td>
					<td width="30%" align="center"><input type="text" align="center" size="53" name="form_nombre" value=""></td>
					<input type="hidden" align="center" size="53" name="form_cif" value="<?php echo $cif_cliente;?>">
					</tr>
					<tr>
					<td width="20%">IP Pública: </td>
					<td width="30%" align="center"><input type="text" size="53" name="form_ippublica" value=""></td>
					</tr>
					</table> 					
					
					</div>
					<p align="center">&nbsp;</p>
					<div align="center">
					
					<input type="submit" value="Añadir Call Center" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	&nbsp;&nbsp;&nbsp;

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