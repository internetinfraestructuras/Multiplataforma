<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");
//include('routeros_api.class.php');

$usuario=$_SESSION['nombre_usuario'];
//$fechadesde=$_POST['fechadesde'];
//$centralita=$_POST['centralitaseleccionada'];
require('routeros_api.class.php');


//para ordenar por nombre
//$ordenacion= $_GET['orden'];

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

function   mostrarTablaPPPUsers($ARRAY,$prefijo){
	
	//solo muestro los usuarios que empiecen por dicho prefijo
	
					   
	echo "<table>";
	echo "<caption>Clientes</caption><thead><tr><td>Cliente</td><td>Usuario</td>
	<td>Password</td><td>Servicio</td><td>Profile</td><td>Direccion IP</td><td>Editar</td><td>Señal</td></thead><tbody>";
	
	foreach($ARRAY as $posicion=>$jugador)
	{
		//echo "El " . $posicion . " es " . $jugador;
		$disabled="";
		$id="";
		$name="";		
		$service="";
		$password="";
		$profile="";
		$ip="-DHCP-";			
		$comment="-SIN NOMBRE-";	
				
		foreach($jugador as $posicion2=>$jugador2)
		{
			//echo "El----- " . $posicion2 . " es " . $jugador2;
			//echo "<br>";
			if($posicion2=="disabled")
				$disabled=$jugador2;					
			if($posicion2==".id")
				$id=$jugador2;
			if($posicion2=="name")
				$name=$jugador2;			
			if($posicion2=="service")
				$service=$jugador2;
			if($posicion2=="password")
				$password=$jugador2;
			if($posicion2=="profile")
				$profile=$jugador2;
			if($posicion2=="remote-address")
				$ip=$jugador2;			
			if($posicion2=="comment")
				$comment=$jugador2;			
		}
		
		/*if($disable==false)
			$disable="false";
		else if($disable==true)
				$disable="true";
		*/
		//extraigo prefijo del name
		$prefijoName=substr($name, 0, 3);
		$pos = strpos($name, $prefijo);
		if ($prefijo==$prefijoName)
		{			
			if($disabled=="false")
			{	
			echo "<tr><td>$comment</td><td>$name</td><td>$password</td><td>$service</td>
			<td>$profile</td><td>$ip</td><td><a 
			title='Editar Cliente'  
			href=\"editar_cliente.php?idcliente=$id&name=$name&service=$service&password=$password&
			profile=$profile&ip=$ip&comment='$comment'&disabled=$disabled\">Editar</a>
			<td><a 
			title='Ver Señal'  
			href=\"senal_cliente_password.php?name=$name\">Ver Señal</a></td></tr>";
			}
			else
			{
			echo "<tr style='background: red;'><td>$comment</td><td>$name</td><td>$password</td><td>$service</td>
			<td>$profile</td><td>$ip</td><td><a 
			title='Editar Cliente'  
			href=\"editar_cliente.php?idcliente=$id&name=$name&service=$service&password=$password&
			profile=$profile&ip=$ip&comment='$comment'&disabled=$disabled\">Editar</a></td>
			<td><a 
			title='Ver Señal'  
			href=\"senal_cliente_password.php?name=$name\">Ver Señal</a></td></tr>";

			}
		}
		
		//echo "<br>";
	}
	
	echo "</tbody></table>";
	
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
			
					<h4 class="title">Clientes</h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					

					
					<?php
					/*
					para añdir futuro
					$API->comm("/ppp/secret/add", array(
					  "name"     => "user",
					  "password" => "pass",
					  "remote-address" => "172.16.1.10",
					  "comment"  => "{new VPN user}",
					  "service"  => "pptp",
					));
					*/
					
					echo "<table width='100%'>";
				

				
					$API = new RouterosAPI();

					//para que no salga el churro gordo por pantalla
					//$API->debug = true;
					
					$routerIP=routerIP($usuario);
					$routerUsuario=routerUsuario($usuario);
					$routerPassword=routerPassword($usuario);
					
					//echo $routerIP."--".$routerUsuario."---".$routerPassword;

					if ($API->connect($routerIP,$routerUsuario,$routerPassword)) {

					   $API->write('/ppp/secret/getall');

					   $READ = $API->read(false);
					   $ARRAY = $API->parseResponse($READ);

					  // print_r($ARRAY); print

					   $API->disconnect();
					   
					  // echo "<br>-- <br><br>";
					  $prefijo=prefijoSuperusuario($usuario);
					  
					   mostrarTablaPPPUsers($ARRAY,$prefijo);
					   
					   

					}		
				
					echo "</table>";
					
					
					//	background: #f65656;
				
					
					?>
					
					<!--mostrar o no los clientes por colores-->
					
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
