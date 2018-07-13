<?php
session_start();
session_destroy();
include ("funciones.php");
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >


<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="ClanTemplates" />
	<meta name="keywords" content="Clan" />
	<meta name="description" content="Grupo REQ WISP Admin" />
	<meta name="robots" content="all" />
	<title>WISP Admin</title>

	<style type="text/css" title="currentStyle" media="screen">
		 @import "style/stylesheet.css";
	</style>
	<!--link rel="Shortcut Icon" type="image/x-icon" href="images/favicon.ico" /-->	
</head>
<body>

<div id="container">
	<!-- <div id="header">
		<h3 class="welcome"><span>WISP Admin</span></h3>
		<fieldset class="quick-login">
			<legend>Autenticar</legend>
			<label for="username">Usuario</label> <input type="text" id="username" name="username" value="usuario" class="loginbox" title="Username" />
			<?php echo $_SESSION['nombre_usuario']; ?>

		</fieldset> 
	</div> -->
	<div id="banner">

	<div align="right">
	</div>


	<h1 id="logo"><a href="#" title="WISP Admin">WISP Admin</a></h1>
	</div>
	<ul id="navigation">

	</ul>
	<!-- contenedor principal donde desplegar informacion -->
	<div align="center">
		<div align="center">
			<div class="article" align="center">	
				<div class="story" align="center">
					<h4 class="title">WISP Admin</h4>	

					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
						<b>Desconectado de la Plataforma</b>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<center>
					<form action="index.php">
					<input type="submit" Value="Autenticar Usuario">
					</form>
					
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>

				</div>
				
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
