<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");
require('routeros_api.class.php');

$usuario=$_SESSION['nombre_usuario'];
$form_cif=$_POST['form_cif'];
$form_cif_original=$_POST['form_cif_original'];
$form_nombre=$_POST['form_nombre'];
$form_profile=$_POST['form_profile'];
$form_opcionip=$_POST['form_opcionip'];
$form_direccionip=$_POST['form_direccionip'];
$form_service=$_POST['form_service'];
$password=$_POST['form_password'];


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
					if($password=="" )
					{
						$error=1;
						$cadena_error= $cadena_error ."Password vacio<br>";
					}								

						
					if($error==0)
					{
					
							//elimino el usuario anterior
							$API = new RouterosAPI();
							//para que no salga el churro gordo por pantalla
							//$API->debug = true;
							

                                        $routerIP=routerIP($usuario);
                                        $routerUsuario=routerUsuario($usuario);
                                        $routerPassword=routerPassword($usuario);

							if ($API->connect($routerIP, $routerUsuario,  $routerPassword)) {		
									$API->comm("/ppp/secret/remove", array(
									  ".id"     => "$form_cif_original",
									));		
							}
								
							$API->disconnect();						
						
							//creo el nuevo usuario
							
							$prefijo=prefijoSuperusuario($usuario);
							
							$form_nombre=$prefijo.$form_nombre;
							$form_cif=$prefijo.$form_cif;
							

							//para que no salga el churro gordo por pantalla
							//$API->debug = true;

                                        $routerIP=routerIP($usuario);
                                        $routerUsuario=routerUsuario($usuario);
                                        $routerPassword=routerPassword($usuario);

 					if ($API->connect($routerIP, $routerUsuario,  $routerPassword)) {
							   //$API->write('/ppp/secret/getall');
								if($form_direccionip!="")
								{	
										$API->comm("/ppp/secret/add", array(
										  "name"     => "$form_cif",
										  "password" => "$password",
										  "remote-address" => "$form_direccionip",
										  "comment"  => "$form_nombre",
										  "service"  => "$form_service",
										  "profile"  => "$form_profile",
										));	
								}
								else
								{
										$API->comm("/ppp/secret/add", array(
										  "name"     => "$form_cif",
										  "password" => "$password",
										  "comment"  => "$form_nombre",
										  "service"  => "$form_service",
										  "profile"  => "$form_profile",
										));							
									
								}	
								
								//$READ = $API->read(false);
								//$ARRAY = $API->parseResponse($READ);

								//print_r($ARRAY);

								$API->disconnect();					   
							}							
					

					?>
					
					<h4 class="title">Modificado <?php echo $form_cif;?></h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
					
					Cliente modificado correctamente.<br><br><br>
					<form action="clientes.php">
					<input type="submit" value="Volver a Clientes" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 
					
					</div>
					<p align="center">&nbsp;</p>
					
	             <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
				 
	
					<?php 
					
					}
					else
					{
					
					
					?>
					
					<h4 class="title">Modificar  Cliente - Error!</h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
					<?php
					
					echo $cadena_error;
					
					
					//boton volver a alta cliente
					?>
					<p>&nbsp;</p><p>&nbsp;</p>
					<form action="clientes.php" method="post">
					
					<input type="submit" value="Volver" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 
					</form>					
					</div>
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
