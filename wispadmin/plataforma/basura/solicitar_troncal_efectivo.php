<?php

include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$sugerencia_did=$_POST['form_did'];
$cif_cliente=$_POST['form_cif'];
$nombrecallcenter=$_POST['form_callcenter'];
$callcenter=$_POST['form_callcenter'];
$id_centralita=$_POST['form_id_centralita'];
$protocolo=$_POST['form_protocolo'];
$codecs=$_POST['form_codecs'];
					
				
	
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
					  
					$var = " /usr/sendEmail/sendEmail -f plataforma.prepago@gmail.com -t paco.perez@gruporeq.com -s smtp.gmail.com -u Plataforma REQ: Solicitud Creacion de troncal   -v -o tls=yes -xu plataforma.prepago -xp gruporeq1430 -m SuperUsuario: $usuario, Cliente: $cif_cliente , Callcenter: $callcenter Protocolo: $protocolo, Codecs: $codecs, DID:$sugerencia_did. HAY QUE CREAR LA TRONCAL Y EL DPLN EN TUKU01";
					//echo $var;
					exec($var);
					
					//bloqueamos la nueva peticion de troncales
					
					//conectamos a  MYSQL

					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
					
						
					$sel = " update centralitas set habilitado = 'ptron' where id_centralita='$callcenter'";
				
					$query = mysql_query($sel) or die(mysql_error());						
					

					?>
					
					<h4 class="title">Solicitud Troncal<?php echo $form_cif;?></h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
					
					Solicitud enviada con exito.<br><br><br>
					El Call Center permanecerá en estado de trámite hasta la creación/modificación de troncales.<br><br><br>
					
					<form type="POST" action="gruposderecarga.php">
					<input type="button" value="Volver a Grupos de Recarga"  onClick="location.href = 'gruposderecarga.php'"  style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 

					</form>
					
					</p>
					<p>

					
					
	             <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
	
					</div>
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
</div>

</body>
</html>
