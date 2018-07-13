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
			
					<h4 class="title">Estado Troncales (test feature)</h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					

					
					<?php
					
					
					
					
					echo "<table width='100%'>";
					echo 
"<caption>Estado de las troncales de los clientes</caption><thead><tr><td>CIF</td><td>Cliente</td><td>Troncal</td><td>Estado</td><td>IP Registro</td><td>Fecha Actualización</td></thead><tbody>";

					$colorfila=0;	
					//conectamos a  MYSQL

					$usu=cif_superusuario($usuario);	
					
					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
						
						
					$sel = "
select usuarios.cif,usuarios.nombre,centralitas.id_centralita,troncales.usuario_troncal,troncales.estado,troncales.iporigen,
troncales.fechaactualizacion from centralitas,usuarios,troncales where 
centralitas.cif_user = usuarios.cif and troncales.id_centralita = centralitas.id_centralita and cif_super='$usu' order by troncales.estado desc";
					$query = mysql_query($sel) or die(mysql_error());
					$mostrarleyenda=0;	
					$mostrarleyenda_bloq=0;
					while ($row = mysql_fetch_assoc($query)) 
					{
					

						if($row['estado']=="UP")
						{
							echo "<tr class=\"up\" ><td>".$row['cif']."</td><td>".$row['nombre']."</td>
							<td>".$row['usuario_troncal']."</td><td>".$row['estado']."</td><td>".$row['iporigen']."</td><td>".$row['fechaactualizacion']."</td></tr>";
					    }
						else
						{

							
						   echo "<tr class=\"down\" ><td>".$row['cif']."</td><td>".$row['nombre']."</td>
							<td>".$row['usuario_troncal']."</td><td>".$row['estado']."</td><td>".$row['iporigen']."</td><td>".$row['fechaactualizacion']."</td></tr>";
	
					    }	
						$colorfila++;
					}						
					
					mysql_close($db);		
						
					echo "</tbody>";
					echo "</table>";
					
					
					//	background: #f65656;
					echo "<p>&nbsp;</p><p>&nbsp;</p>";
					
					
					
					?>
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

	<!-- el footer de todalavida-->
		<div id="footer">
		<?php pie(); ?>
	    </div>
</div>

</body>
</html>
