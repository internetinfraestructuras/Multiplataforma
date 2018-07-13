<?php

include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");
require('routeros_api.class.php');

$usuario=$_SESSION['nombre_usuario'];
$form_cif=$_POST['form_cif'];
$form_nombre=$_POST['form_nombre'];
$form_profile=$_POST['form_profile'];
$form_opcionip=$_POST['form_opcionip'];
$form_direccionip=$_POST['form_direccionip'];
$form_service=$_POST['form_service'];


//quito los espacios del cif que peta luego todo
$form_cif=str_replace(' ', '', $form_cif);


function checkIPAddress($ipAddress) 
{
    return preg_match('/^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}$/', $ipAddress);
}





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
	<title>WISP Admin Grupo REQ</title>

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
	    <h1 id="logo"><a href="#" title="WISP Admin">WISP Admin</a></h1>
	</div>
	
	<ul id="navigation">

	</ul>
	<!-- contenedor principal donde desplegar informacion -->
	<div id="main">
		<div id="content">
			         
					<?php
					  
					//$cif_super=cif_superusuario($usuario);	  
						
					//verifico que todo viene relleno
					$error=0;
					$cadena_error="Error:<br><p>&nbsp;</p><p>&nbsp;</p>";
					if($form_nombre=="")
					{
						$error=1;
						$cadena_error= $cadena_error ."Usuario plataforma incorrecto<br>";
					}
					if($form_cif=="")
					{
						$error=1;
						$cadena_error= $cadena_error ."CIF incorrecto<br>";
					}
					if($form_opcionip=="estatica" && $form_direccionip=="")
					{
						$error=1;
						$cadena_error= $cadena_error ."Direccion ip estatica vacia o incorrecta<br>";
					}
					if($form_direccionip!="" && !checkIPAddress($form_direccionip))
					{
						$error=1;
						$cadena_error= $cadena_error ."Direccion ip incorrecta<br>";
					}
					if($form_opcionip=="" )
					{
						$error=1;
						$cadena_error= $cadena_error ."No marcado modo de asignacion de ip<br>";
					}					
					//falta aki verificar una direccion ip

						
					if($error==0)
					{
						//generar el password!!! aki
						$form_password=getRandomCode();
						
						$prefijo=prefijoSuperusuario($usuario);
						
						$form_nombre=$prefijo.$form_nombre;
						$form_cif=$prefijo.$form_cif;
						
						
						//aki lo metemos	
					/*	echo "lo damos de alta";
						echo $form_cif;
						echo $form_nombre;
						echo $form_profile;
						echo $form_opcionip;
						echo "||".$form_direccionip."||";*/
						
						$API = new RouterosAPI();

						//para que no salga el churro gordo por pantalla
						//$API->debug = true;

					$routerIP=routerIP($usuario);
					$routerUsuario=routerUsuario($usuario);
					$routerPassword=routerPassword($usuario);

						//echo $routerIP."-----".$routerUsuario."----------".$routerPassword."<br>";

						if ($API->connect( $routerIP, $routerUsuario,  $routerPassword)) {

						   //$API->write('/ppp/secret/getall');
						if($form_direccionip!="")
						{	
							$API->comm("/ppp/secret/add", array(
							  "name"     => "$form_cif",
							  "password" => "$form_password",
							  "remote-address" => "$form_direccionip",
							  "comment"  => "$form_nombre",
							  "service"  => "$form_service",
							  "profile"  => "$form_profile"
							));	
						}
						else
						{
							$API->comm("/ppp/secret/add", array(
							  "name"     => "$form_cif",
							  "password" => "$form_password",
							  "comment"  => "$form_nombre",
							  "service"  => "$form_service",
							  "profile"  => "$form_profile"     //
							));							
							
						}	
							
						   //$READ = $API->read(false);
						   //$ARRAY = $API->parseResponse($READ);

						   //print_r($ARRAY);

						   $API->disconnect();					   
					}								
						
					/*
					para a�dir futuro
					$API->comm("/ppp/secret/add", array(
          "name"     => "user",
          "password" => "pass",
          "remote-address" => "172.16.1.10",
          "comment"  => "{new VPN user}",
          "service"  => "pptp",
));
*/						
						

					?>
					
					<h4 class="title">Alta Cliente</h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
				    <table  border="1" cellpadding="0" cellspacing="0">
					<caption>Alta Cliente: <?php echo $form_cif;?>.</caption>
					</table>
					
					Almacenados datos del cliente: se conectara con usuario: <b><?php echo $form_cif;?></b>
					y contrase�a <b><?php echo $form_password;?></b>
					<br><br><br>

					</div>
					<p align="center">&nbsp;</p>
					
					<div align="center">
						<form action="clientes.php">
						<input type="submit" value="Volver a Clientes" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 
						</form>
					</div>

					<?php 
					
					}
					else
					{
					
					
					?>
					
					<h4 class="title">Alta Cliente - Error!</h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
					<?php
					
					echo $cadena_error;
					
					
					//boton volver a alta cliente
					?>
					<p>&nbsp;</p><p>&nbsp;</p>
					<form action="altacliente.php" method="post">
					
					<input type="submit" value="Volver" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 
					</form>					
					</div>
					<?php } ?>
					
					
					
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

</body>
</html>
