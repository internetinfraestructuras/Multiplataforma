<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
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

</head>
<body>

<div id="container">	

	<div id="banner">
	    <?php mostrar_datos_user($_SESSION['nombre_usuario']) ?>
	    <h1 id="logo"><a href="#" title="Plataforma Prepago">Plataforma Prepago</a></h1>
	</div>	<ul id="navigation">

	</ul>
	<!-- contenedor principal donde desplegar informacion -->
	<div id="main">
		<div id="content">
			<div class="article">	
				<div class="story">
					<h4 class="title">Saldo Actual</h4>	
					
					<?php
					$usuario=$_SESSION['nombre_usuario'];
					$cif = cif_superusuario($usuario);	
						
						//conectamos a  MYSQL
						$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
						// Seleccionamos la Base de datos
						mysql_select_db($nombre_bd) or die(mysql_error());
						//realizamos la consulta
						$result = mysql_query("select saldo from superusuarios where cif_superuser='$cif'",$db); 

						//seleccionamos la fila
						$array= mysql_fetch_row($result);
						//selecciomnamos el dato
						$saldo= $array[0];
						mysql_close($db);		

						//de regalo obtengo la fecha
						$fecha = date("Y-m-d H:i:s");
						
						echo "<p>Su saldo a <b>$fecha</b> es de : <b> ".redondear_tres_decimal($saldo)."</b> Euros</p>";
					?>
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
	<!-- el footer de todalavida-->
	<div id="footer">
		<?php pie(); ?>
	</div>
</div>


</body>
</html>