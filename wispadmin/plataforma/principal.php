<?php
include ("bloquedeseguridad.php");
include ("configuracion.php");
include("funciones.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="REQ" />
	<meta name="keywords" content="WISP Admin" />
	<meta name="description" content="REQ WISP Admin" />
	<meta name="robots" content="all" />
	<title><?php title();?></title>

	<style type="text/css" title="currentStyle" media="screen">
		 @import "style/stylesheet.css";
	</style>
</head>
<body>

<div id="container">

	<div id="banner">
	<?php mostrar_datos_user($_SESSION['nombre_usuario']) ?>
	<h1 id="logo"><a href="#" title="WISP Admin">WISP Admin</a></h1>
	</div>
	
	<ul id="navigation">

	</ul>
	<!-- contenedor principal donde desplegar informacion -->
	<div id="main">
		<div id="content">
			<div class="article">	
				<div class="story">
					<h4 class="title">WISP Admin</h4>	
					<p>Bienvenido a la plataforma de administracion de usuarios finales para WISP</p>
					<p>Le damos la bienvenida a la plataforma WISP Admin de Nexwrf donde podr&aacute; administrar el acceso al servicio de sus distintos usuarios</p>
				</div>
				
				<div class="more">
				
     			<div class="author-image">
				<a href="http://plataforma.prepago" title="WISP Admin">
				<img src="images/users/reqtelefonia.jpg" alt="reqtelefonia" />
				</a>
				</div>
				
				</div>
	
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
	
	<!-- el footer-->
	<div id="footer">
		<?php pie(); ?>
	</div>
</div>


</body>
</html>
