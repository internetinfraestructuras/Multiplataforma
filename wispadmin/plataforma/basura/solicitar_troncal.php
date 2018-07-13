<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$cif=$_POST['cif'];
$callcenter=$_POST['callcenter'];




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
					<h4 class="title">Solicitud nueva troncal</h4>
					<form action="solicitar_troncal_efectivo.php" method="post" name="form_cliente" width="50%">
					<div align="center">
					
					</p>
					<p>

					<table  border="1" cellpadding="0" cellspacing="0" width="50%">
					<caption> Solicitud nueva troncal cliente: <?php echo $cif;?> callcenter nº: <?php echo $callcenter;?></caption>
					<tr>
					<td width="20%">Protocolo (SIP/IAX2): </td>
					<td width="30%" align="center"><input type="text" align="center" size="53" name="form_protocolo" value=""></td>
					</tr>
					<tr>
					<td width="20%">Codecs (gsm,ulaw,alaw,g729,ilbc): </td>
					<td width="30%" align="center"><input type="text" align="center" size="53" name="form_codecs" value=""></td>
					</tr>
					<tr>
					<td width="20%">Sugerencia numérico de Salida: </td>
					<td width="30%" align="center"><input type="text" align="center" size="53" name="form_did" value=""></td>
					</tr>
					<input type="hidden" align="center" size="53" name="form_cif" value="<?php echo $cif;?>">					
					<input type="hidden" align="center" size="53" name="form_callcenter" value="<?php echo $callcenter;?>">
					<input type="hidden" align="center" size="53" name="form_id_centralita" value="<?php echo $id_centralita;?>">
					</tr>
					</table> 					
					
					</div>
					<p align="center">&nbsp;</p>
					<div align="center">
					
					<input type="submit" value="Enviar Solicitud" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	&nbsp;&nbsp;&nbsp;

					</div>
					</form>	
					
	             <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
				 
				 <?php
				    //SE MUDA ESTO AL PASO FINAL YA K SI EL NOTA SE ARREPIENTE SE QUEDA TODO BLOQUEADO
					//tras la solicitud se pone la centralita como si existieses troncales por crear pendientes
					
					//conectamos a  MYSQL

					//$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					//mysql_select_db($nombre_bd) or die(mysql_error());
					
						
					//$sel = " update centralitas set habilitado = 'ptron' where id_centralita='$callcenter'";
				
					//$query = mysql_query($sel) or die(mysql_error());	
				?>
	
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