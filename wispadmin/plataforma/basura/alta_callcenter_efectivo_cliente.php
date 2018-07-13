<?php

include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$form_cif=$_POST['form_cif'];
$form_nombre=$_POST['form_nombre'];
$form_ip=$_POST['form_ippublica'];


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
						
					

						
					$sel = "insert into centralitas (cif_user,nombre,ip_publica,habilitado) VALUES ('$form_cif','$form_nombre','$form_ip','ptron')";
				
					$query = mysql_query($sel) or die(mysql_error());
					
					
					//saco el id de la centralita/callcenter creado para enviarlo por la solicitud
					
					$sel = "select id_centralita from centralitas where cif_user='$form_cif' and nombre='$form_nombre'and ip_publica='$form_ip'";
				
					$query = mysql_query($sel) or die(mysql_error());
					
					$ret = mysql_fetch_array($query);  
					$id_centralita= $ret['id_centralita']; 
						
					mysql_close($db);		
						
						

					?>
					
					<h4 class="title">Alta Call Center <?php echo $form_cif;?></h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
					
					Alta realizada con exito.<br><br><br>
					El callcenter permanecerá en estado de trámite hasta la creación de troncales.<br><br>
					Enviar solicitud de troncal para este cliente:
					<form action="enviar_mail_solicitud.php" method="post" name="form_cliente" width="50%">
					<div align="center">
					
					</p>
					<p>

					<table  border="1" cellpadding="0" cellspacing="0" width="50%">
					<caption> Solicitud nueva troncal cliente: <?php echo $form_cif;?> callcenter nº: <?php echo $id_centralita;?></caption>
					<tr>
					<td width="20%">Protocolo (SIP/IAX2): </td>
					<td width="30%" align="center"><input type="text" align="center" size="53" name="form_protocolo" value=""></td>
					</tr>
					<tr>
					<td width="20%">Codecs (gsm,ulaw,alaw,g729,ilbc): </td>
					<td width="30%" align="center"><input type="text" align="center" size="53" name="form_codecs" value=""></td>
					<tr>
					</tr>
					<td width="20%">Sugerencia numérico de Salida: </td>
					<td width="30%" align="center"><input type="text" align="center" size="53" name="form_did" value=""></td>
					<tr>
					</tr>
					</table> 
					<input type="hidden" align="center" size="53" name="form_cif" value="<?php echo $form_cif;?>">					
					<input type="hidden" align="center" size="53" name="form_callcenter" value="<?php echo $cif;?>">
					<input type="hidden" align="center" size="53" name="form_id_centralita" value="<?php echo $id_centralita;?>">

					
					</div>
					<p align="center">&nbsp;</p>
					<div align="center">
					
					<input type="submit" value="Enviar Solicitud" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	&nbsp;&nbsp;&nbsp;

					</div>
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
</div>
</body>
</html>
