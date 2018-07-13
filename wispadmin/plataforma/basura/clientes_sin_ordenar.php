<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
//$fechadesde=$_POST['fechadesde'];
//$centralita=$_POST['centralitaseleccionada'];

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
			
					<h4 class="title">Clientes</h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					

					
					<?php
					
					
					echo "<table width='100%'>";
					
					//si he entrado como adminpostpago mis clientes tendran una columna adicional que será historico mensual
					
					if($usuario=="adminpostpago" || $usuario=="jersatelecom")
					{					
					echo 
"<caption>Clientes</caption><thead><tr><td>Tipo Fact.</td><td>CIF</td><td>Nombre</td><td>Email</td><td>Call-Centers</td><td>€/Mins.</td><td>Consumo Mes</td><td>Recargas</td></thead><tbody>";

					}
					else
					{
					echo 
"<caption>Clientes</caption><thead><tr><td>Tipo Fact.</td><td>CIF</td><td>Nombre</td><td>Email</td><td>Call-Centers</td><td>€/Mins.</td><td>Recargas</td></thead><tbody>";

					}
					

					$colorfila=0;	
					//conectamos a  MYSQL

					$usu=cif_superusuario($usuario);	
					
					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
						
						
					$sel = "select * from usuarios where cif_super='$usu'";
					$query = mysql_query($sel) or die(mysql_error());
					$mostrarleyenda=0;	
					$mostrarleyenda_bloq=0;
					while ($row = mysql_fetch_assoc($query)) 
					{
					
						if($row['habilitado']=='no')
						{ 
						  $habi="disabled=\"true\" onclick=\"return false\"";
						  $colordeshabilitado= "class=\"deshabilitado\"";
						  $mostrarleyenda=1;
						}
						else if($row['habilitado']=='bloq')
						{
						  $colordeshabilitado= "class=\"bloqueado\"";
						  $mostrarleyenda_bloq=1;	
						}
						else
						{
						   $habi="";
						   $colordeshabilitado="";

						}
						
	
					
						
					$tipo=$row['tipofacturacion'];
							
					//cojo el prefijo por si en un futuro hay distintos tipos de tarifas que si MPRE600 SPRE15 y esas cosas							
							$subtipo = substr($tipo, 0,4);	

						if($subtipo=="SPRE" || $subtipo=="SPOS")
							$especie=" €";
						else if($subtipo=="MPRE")
							 $especie=" min";
							 
 

						if($colorfila%2==0)
						{
						
							if($usuario=="adminpostpago" || $usuario=="jersatelecom")
							{	
							
							echo "<tr $colordeshabilitado ><td>$tipo</td><td><a 
title='Editar Cliente'  href=\"editar_cliente.php?cliente=".$row['cif']."  
\">".$row['cif']."</a></td><td>".$row['nombre']."</td><td><a  ".$habi." 
href=\"mailto:".$row['email']."\">".$row['email']."</a></td><td><a title='Centers'  ".$habi." 
href=\"callcenters.php?cliente=".$row['cif']."\">Centers</a></td><td><a title='Recargar Cliente'  ".$habi." 
href=\"recarga_cliente.php?cliente=".$row['cif']."\">".redondear_tres_decimal($row['saldo']).$especie."</a></td>
<td><a title='Consumo Mensual'  
".$habi." href=\"consumo_mensual.php?cliente=".$row['cif']."\">Mensual</a></td>
<td><a title='Transpasos Cliente'  
".$habi." href=\"historicotraspasos.php?cliente=".$row['cif']."\">Hist.</a></td></tr>";
					    
							}
							else
							{
								echo "<tr $colordeshabilitado ><td>$tipo</td><td><a 
title='Editar Cliente'  href=\"editar_cliente.php?cliente=".$row['cif']."  
\">".$row['cif']."</a></td><td>".$row['nombre']."</td><td><a  ".$habi." 
href=\"mailto:".$row['email']."\">".$row['email']."</a></td><td><a title='Centers'  ".$habi." 
href=\"callcenters.php?cliente=".$row['cif']."\">Centers</a></td><td><a title='Recargar Cliente'  ".$habi." 
href=\"recarga_cliente.php?cliente=".$row['cif']."\">".redondear_tres_decimal($row['saldo']).$especie."</a></td><td><a title='Transpasos Cliente'  
".$habi." href=\"historicotraspasos.php?cliente=".$row['cif']."\">Hist.</a></td></tr>";
					    							
							
							
							}
						
						
						
						}
						else
						{

							if($usuario=="adminpostpago" ||$usuario=="jersatelecom")
							{	
							
							echo "<tr $colordeshabilitado ><td>$tipo</td><td><a 
title='Editar Cliente'  href=\"editar_cliente.php?cliente=".$row['cif']."  
\">".$row['cif']."</a></td><td>".$row['nombre']."</td><td><a  ".$habi." 
href=\"mailto:".$row['email']."\">".$row['email']."</a></td><td><a title='Centers'  ".$habi." 
href=\"callcenters.php?cliente=".$row['cif']."\">Centers</a></td><td><a title='Recargar Cliente'  ".$habi." 
href=\"recarga_cliente.php?cliente=".$row['cif']."\">".redondear_tres_decimal($row['saldo']).$especie."</a></td>
<td><a title='Consumo Mensual'  
".$habi." href=\"consumo_mensual.php?cliente=".$row['cif']."\">Mensual</a></td>
<td><a title='Transpasos Cliente'  
".$habi." href=\"historicotraspasos.php?cliente=".$row['cif']."\">Hist.</a></td></tr>";
					    
							}
							else
							{						
						
							
						   echo "<tr $colordeshabilitado ><td>$tipo</td><td><a title='Editar 
Cliente' href=\"editar_cliente.php?cliente=".$row['cif']."\"  
>".$row['cif']."</a></td><td>".$row['nombre']."</td><td><a  ".$habi." 
href=\"mailto:".$row['email']."\">".$row['email']."</a></td><td><a title='Centers'  ".$habi." 
href=\"callcenters.php?cliente=".$row['cif']."\">Centers</a></td><td><a title='Recargar Cliente'  ".$habi." 
href=\"recarga_cliente.php?cliente=".$row['cif']."\">".redondear_tres_decimal($row['saldo']).$especie."</a></td><td><a title='Transpasos Cliente'  
".$habi." href=\"historicotraspasos.php?cliente=".$row['cif']."\">Hist.</a></td></tr>";
							}
							
						}	
						$colorfila++;
					}						
					
					mysql_close($db);		
						
					echo "</tbody>";
					echo "</table>";
					
					
					//	background: #f65656;
					echo "<p>&nbsp;</p><p>&nbsp;</p> <p align='right'>SPRE = Facturación prepago en euros <br> MPRE = Facturación prepago en minutos </p><p>&nbsp;</p>";
					
					if($mostrarleyenda==1)
					{
					
					   echo "
				
					   <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
					
					   <div align='right'>
					
					   <img src='rojo.jpg'>&nbsp;</img>&nbsp;&nbsp;Pendiente de Alta Técnica
							
					   </div>";
					}
					   
					if( $mostrarleyenda_bloq==1)
					{
					
					   echo "		
					   
					   <div align='right'>
					
					   <img src='amarillo.jpg'>&nbsp;</img>&nbsp;&nbsp;Bloqueado&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							
					   </div>";	
					   		   
					}
					
					
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
