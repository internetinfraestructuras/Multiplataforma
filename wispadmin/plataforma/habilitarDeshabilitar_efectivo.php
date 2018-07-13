<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");
require('routeros_api.class.php');

$usuario=$_SESSION['nombre_usuario'];
$name=$_POST['name'];
$habilitado=$_POST['form_habilitado'];

function obtenerNumber($ARRAY,$name){
	
	//solo muestro los usuarios que empiecen por dicho prefijo
	$number="NULL";
	foreach($ARRAY as $posicion=>$jugador)
	{
		//echo "El " . $posicion . " es " . $jugador;
		
		foreach($jugador as $posicion2=>$jugador2)
		{
			//echo "El----- " . $posicion2 . " es " . $jugador2;
			//echo "<br>";
			if($jugador2==$name)
				$number=$posicion;					
		
		}
		
		//echo "<br>";
	}
	
	return $number;
	
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
	<title>WISP Admin</title>

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
					
					
					
					//habilitamos o deshabilitamos de la RB

							$API = new RouterosAPI();

						//para que no salga el churro gordo por pantalla
						//$API->debug = true;

						   $routerIP=routerIP($usuario);
                           $routerUsuario=routerUsuario($usuario);
                           $routerPassword=routerPassword($usuario);

										
						if ($API->connect( $routerIP, $routerUsuario,  $routerPassword)) {
							
							if($habilitado=="si") //=> disabled =false => enable
							{	
								$API->comm("/ppp/secret/enable", array(
								"numbers"     => "$name",
								));		
							
							}
							else  //disabled=True
							{
								$API->comm("/ppp/secret/disable", array(
								"numbers"     => "$name",
								));	
								
								//si lo deshabilito ademas lo he de desconectar
								// para ello he de hacer un ppp active print
								//sacar el numero y luego dar
								//ppp active remove numero

								$ARRAY = $API->comm("/ppp/active/print",  array(
								  ".proplist"=> "name",
								));
										
										//print_r($ARRAY);
										//echo "--------------------------------<br><br><br>";
								$number=obtenerNumber($ARRAY,$name);
								
								$API->comm("/ppp/active/remove", array(
								"numbers"     => "$number",
								));				
								//echo "<br><br>yeaaaa el num es $number<br><br>";								

							}

							
						}
						
						$API->disconnect();	
			
					
						

					?>
					
					<h4 class="title">Modificacion Efectuada <?php echo $name;?></h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
					
					Modificacion efectuada con éxito.<br><br><br>
					<p>

					</p>
					<p>
					&nbsp;
					</p>
					
					<form action="clientes.php">
					<input type="submit" value="Volver a Clientes" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 
					
					</div>
					<p align="center">&nbsp;</p>
					
			
					
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
