<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$cif=$_GET['cif'];
$importe=$_GET['importe'];
$superusu=$_GET['cifsuper'];



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
			
			         
					<?php
					  
//------------------------------------------------------------------------------------------------------------------					
					//superbloque verificacion legitimidad
					//como el id del cliente viene por GET, si un listo lo cambia puede acceder a los clientes
					//de otra pe�a asi que verifico que el cliente con ese cif es propietario del tio que cursa la sesion
					
					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
						
						
					$sel = "select superusuario from superusuarios where cif_superuser = (select cif_super
					from usuarios where cif = '$cif')";
					$query = mysql_query($sel) or die(mysql_error());
						
					while ($row = mysql_fetch_assoc($query)) 
					{									
						$superuser_propietario_legitimo=$row['superusuario'];	
					} 			
					mysql_close($db);						
					
					if($usuario!=$superuser_propietario_legitimo)
					{
						//si son distintos => me quieren hacer la gruya!!!
						echo "<p>&nbsp;</p>Error<p>&nbsp;</p>";
						
						//obtengo la ip de acceso desde donde entran
					    $ip_acceso = obtener_direccion_ip();
						$seccion= "confirmacion_recarga.php";
						
						//notifico la violacion
						$var = " /usr/sendEmail/sendEmail -f plataforma.prepago@gmail.com -t paco.perez@gruporeq.com -s smtp.gmail.com -u Plataforma REQ: Intento violacion por metodo GET   -v -o tls=yes -xu plataforma.prepago -xp gruporeq1430 -m EL SuperUsuario: $usuario, intento acceder al callcenter del usuario=$cif_usuario que realmente es de $superuser_propietario_legitimo en la seccion $seccion desde la ip $ip_acceso";
						exec($var);
						
						//cierro sesiones
						session_start();
						session_destroy();
					}
					else
					{					  
					  
					//conectamos a  MYSQL
					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
					
					
					//almacenamos en el historico de recargas
					
					//obtengo el usuario_id
					$sel = "select usuario from usuarios where cif='$cif'";
					$query = mysql_query($sel) or die(mysql_error());						
					$ret = mysql_fetch_array($query);  
					$usuario_recarga=$ret['usuario']; 	

					//obtengo el saldo anterior
					$sel = "select saldo from usuarios where cif='$cif'";
					$query = mysql_query($sel) or die(mysql_error());						
					$ret = mysql_fetch_array($query);  
					$saldo_recarga=$ret['saldo']; 	
					
					//saco el neto
					$neto=$importe + $saldo_recarga;
					//saco la fecha
					$fecha = date("Y-m-d H:i:s");

					
					$sel = "insert into historicorecargas(cif_user,calldate,importe,acumulado,neto) VALUES ('$cif','$fecha','$importe','$saldo_recarga','$neto')";
					$query = mysql_query($sel) or die(mysql_error());						
											
						
						
					//a�adimos el saldo al cliente			
					
					$sel = "update usuarios set saldo = usuarios.saldo + $importe where cif='$cif'";
					
					$query = mysql_query($sel) or die(mysql_error());
						
					//quitamos el saldo al superuser			
					/*
					$sel = "update superusuarios set saldo = superusuarios.saldo - $importe where cif_superuser='$superusu'";
					
					$query = mysql_query($sel) or die(mysql_error());	
					*/
					
					//pongo la notificacion de saldo del cliente a no => notificado=no			
					
					$sel = "update usuarios set notificado='no' where cif='$cif'";
					
					$query = mysql_query($sel) or die(mysql_error());
					
						
					mysql_close($db);		
						
						

					?>
					
					<h4 class="title">Recarga Efectuada <?php echo $form_cif;?></h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
					
					Recarga efectuada con �xito.<br><br><br>
					<form action="clientes.php">
					<input type="submit" value="Volver a Clientes" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 
					
					</div>
					<p align="center">&nbsp;</p>
					
	             <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
				 
				 <?php } ?>
	
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
