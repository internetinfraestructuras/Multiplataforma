<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
//$fechadesde=$_POST['fechadesde'];
//$centralita=$_POST['centralitaseleccionada'];

//para ordenar por nombre
$ordenacion= $_GET['orden'];

//si viene por get es que queremos ordenar por nombre
//la ordenacion puede ser
/*
nombre
cif
email
euros
*/
if(isset($ordenacion)){

//echo "viene ordenacion = $ordenacion";

}
else{

//si no la dejo a normal = cif
$ordenacion="cif";
//echo "viene vacio";

}


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
			
					<h4 class="title">Grupos de Recarga</h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					

					
					<?php
					
					
					echo "<table width='100%'>";
					
					//si he entrado como adminpostpago mis clientes tendran una columna adicional que será historico mensual
					


					echo 
"<caption>Grupos de Recarga</caption><thead><tr><td>Nombre</td>
<td>Importe</td><td>¿Acumulable?</td><td>Color</td></tr></thead><tbody>";	

			                
					$colorfila=0;	
					//conectamos a  MYSQL

					$usu=cif_superusuario($usuario);	
					
					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
						
					$sel = "select * from gruposderecarga where cif_super='$usu' order by nombregrupo";		 						
				

					$query = mysql_query($sel) or die(mysql_error());
					$mostrarleyenda=0;	
					$mostrarleyenda_bloq=0;
					while ($row = mysql_fetch_assoc($query)) 
					{

						echo "<tr>
						<td><a 
title='Editar Grupo'  href=\"editar_grupoderecarga.php?grupoderecarga=".$row['nombregrupo']."&cif_super=$usu  
\">".$row['nombregrupo']."</a></td>
<td>".$row['importerecarga']."</td><td>".$row['acumulable']."</td><td bgcolor=".$row['color'].">&nbsp;</td></tr>";	

					}						
					
					mysql_close($db);		
						
					echo "</tbody>";
					echo "</table>";
					
					?>
						<p>&nbsp;</p>
						<p>&nbsp;</p>
					<form action="alta_grupoderecarga.php" method="post" name="form_centralita" width="30%">
					<div align="center">
							<input type="submit" value="Añadir Grupo de Recarga" style=" width:120;height:20; background-image:url(./images/bg_button_big.gif)"> 	
					
					</div>
					</form>					
					
			
	
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
