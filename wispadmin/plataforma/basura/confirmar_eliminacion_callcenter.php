<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$callcenter=$_GET['callcenter'];

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
						
					
					//piyamos primero quien es el usuaario del callcenter a eliminar para luego volver sobre el listado
					
					$sel = "select cif_user from centralitas where id_centralita='$callcenter'";
				
					$query = mysql_query($sel) or die(mysql_error());
					
					while ($row = mysql_fetch_assoc($query)) 
					{
						$cif_cliente=$row['cif'];
					}					
					
					
					//eliminamos el cliente
					
					$sel = "delete from centralitas where id_centralita='$callcenter'";
					$query = mysql_query($sel) or die(mysql_error());
					
											
						
						
					mysql_close($db);		
						
						

					?>
					
					<h4 class="title">Eliminación Efectuada <?php echo $form_cif;?></h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
					
					Eliminación efectuada con éxito.<br><br><br>
					<p>
					&nbsp;
					</p>
					
					<form>
					<input type="button" value="Volver a Callcenters"  onClick="location.href = 'callcenters.php?cliente=<?php echo $cif_cliente; ?>'"  style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 
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