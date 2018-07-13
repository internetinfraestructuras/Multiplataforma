<?php

include ("configuracion.php");
include("funciones.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >


<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="author" content="NOMBRE" />
	<meta name="keywords" content="NOMBRE" />
	<meta name="description" content="NOMBRE" />
	<meta name="robots" content="all" />
	<title>WISP Admin</title>

	<style type="text/css" title="currentStyle" media="screen">
		 @import "style/stylesheet.css";
	</style>
	<!--link rel="Shortcut Icon" type="image/x-icon" href="images/favicon.ico" /-->	
</head>
<body onload="document.forms[0].nombre_form.focus()">

<div id="container">
	<div id="banner">

	<div align="right">
	</div>


	<h1 id="logo"><a href="#" title="WISP Admin">Plataforma VOIP Grupo REQ</a></h1>
	</div>
	<ul id="navigation">

	</ul>
	<!-- contenedor principal donde desplegar informacion -->
	<div align="center">
		<div align="center">
			<div class="article" align="center">	
				<div class="story" align="center">
					<h4 class="title">WISP Admin</h4>	
					
					
					<p align="center">	
  
					<?php
						$autenti=$_GET["errorusuario"];
						if ($autenti=="si"){
							echo"<font color=\"red\"><b>Usuario o Password incorrectos</b></font><br>";
					} ?>
					</p>
	  
					<p>&nbsp;</p>
					<form action="autentificacion.php" method="POST">
					<div align="center">
						<table border="0">
							<tr>
								<td><b>Nombre de usuario:<b></td>
								<td><input name="nombre_form" size="25" value=""/></td>
							</tr>
							<tr>
								<td><b>Contrase&ntilde;a:<b></td>
								<td><input name="password_form" size="25" type="password"/></td>
							</tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td align="center" colspan='2'> Introduzca el codigo de seguridad</td></tr>
							<tr>
								<td align="center" colspan='2'>
<img align="center" id="captcha" src="securimage/securimage_show.php" height='70px' width='115px' alt="CAPTCHA 
Image" />
								</td> 
							</tr>
							<tr>
<td colspan='2' align="center"> Codigo: <input type="text" name="captcha_code" size="10" maxlength="6" /> </td></tr>

<tr>
<td colspan='2' align="center">
<a href="#" onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); 
return false">[ Generar otra imagen ]</a> 
</td>
</tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>


						</table>
						<input name="submit" type="submit" value="Iniciar sesi&oacute;n"/>
					</div>
					</form>
					
				</div>
				
			</div>
	
		</div>

	</div>
		<!-- el footer-->
	<div id="footer">
		<?php pie(); ?>
	</div>
</div>


</body>
</html>
