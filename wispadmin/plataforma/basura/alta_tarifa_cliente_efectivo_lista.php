<?php

include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$prefijos = $_POST['arv'];
$costes_asociados = $_POST['arvcostes'];
$superuser=$_POST['form_superuser'];
$troncal=$_POST['form_id_troncal'];
$callcenter=$_POST['form_callcenter'];



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
					<h4 class="title">Alta Tarifas asociadas a linea: <?php echo $troncal;?> (confirmación)</h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
					<h3> Destinos y tarifas asociados: </h3>
					<br>&nbsp;<br>
					
					
					<?php

					
					//conectamos a  MYSQL
					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
						
					//echo "Prefijos-> ".$prefijos;
					//echo "costes-> ".$costes_asociados;		
					
					echo "----- Detalles -----<br><br>";
					//$vector_prefijos = explode(',',$prefijos);  
					//$vector_costes = explode(',',$costes_asociados);  
					
					$vector_prefijos = unserialize( stripslashes($prefijos) ); 
					$vector_costes = unserialize( stripslashes($costes_asociados) ); 
					
					$total_no_asignado=1;
					$total_no_asignado=0;								
					//$vector_prefijos = $prefijos;  
					//$vector_costes = $costes_asociados;  
					
					
					//echo "<table width='100%'>";
					//$colorfila=0;
					for($i=0;$i<count($vector_prefijos);$i++)
					{					
						$numero=0;
						$prefix=$vector_prefijos[$i];
						$coste_actual=$vector_costes[$i];
						
						$sel="select * from tarifas_prueba where prefijo='$prefix' and usuario_troncal='$troncal'";
						$result = mysql_query($sel); 
						$numero = mysql_num_rows($result);
						
						//echo $numero."<br>";
						if($numero==0)// => esa tarifa para ese usuario no existe => la asigno
						{
						
							//tenemos que comprobar que el coste que se le asigna al cliente por minuto es mayor o igual que el de
							//redistribucion
							$sel="select prefijo,coste from tarifasminimassuperusuario_prueba where cif_super='$superuser' and prefijo='$prefix'";
					
							//echo "consulta: $sel";

							$query = mysql_query($sel) or die(mysql_error());
				
					
							while ($row = mysql_fetch_assoc($query)) 
							{								
								$prefijo_proveedor=$row['prefijo'];
								$coste_proveedor=$row['coste'];
							}
					
							if($prefijo_proveedor=="")
							{
								$errorcito="Dicha prefijo no esta autorizado en la tabla de redistribución del proveedor";
								$errorcito2="Solicite su autorización y presupuestación al responsable de la plataforma";
								echo "<font color=\"red\" >Prefijo: $prefix no fue asignado</font><br>";									
								echo "<font color=\"red\" >$errorcito</font><br>";
								echo "<font color=\"red\" >$errorcito2</font><br>";	
								$total_no_asignado=$total_no_asignado+1;								
					   
							}
							else if( $coste_actual < $coste_proveedor)//el prefijo concuerda, ahora el coste del proveedor tiene que ser menor que el coste k se pasa
							{
								$errorcito="El coste establecido para el cliente no puede ser inferior al establecido para el revendedor por la plataforma";
								$errorcito2="En este caso el coste por minuto para el destino $prefix ha de ser superior o igual a $coste_proveedor (consulte tabla tarifas redistribucion)";
								
								echo "<font color=\"red\" >Prefijo: $prefix no fue asignado</font><br>";						
								echo "<font color=\"red\" >$errorcito</font><br>";
								echo "<font color=\"red\" >$errorcito2</font><br>";		
								$total_no_asignado=$total_no_asignado+1;								
							}	
							else //todo ok, inserto
							{
								$sel="insert into tarifas_prueba(usuario_troncal,grupo,descripcion,prefijo,coste) select '$troncal',grupo,descripcion,prefijo,'$coste_actual' from tablaprefijos_prueba where prefijo='$prefix'";
								$query = mysql_query($sel) or die(mysql_error());
								echo "<font color=\"green\" >Prefijo: $prefix asignado con exito a $coste_actual €/min</font><br>";
								$total_asignado=$total_asignado+1;
							}
						}
						else
						{
						   	echo "<font color=\"red\" >Prefijo: $prefix no fue asignado porque ya existia para edición simple o supresión acuda a ventana de tarifas</font><br>";
							$total_no_asignado=$total_no_asignado+1;						
						}
						
					}
					echo "<br><br>------------------<br>";
					//resumenes
					if($total_asignado>0)
					{
					 echo "<br><font color=\"green\" >Un total de $total_asignado prefijos fueron asignados</font><br>";
					}				
					
					if($total_no_asignado>0)
					{
					echo "<br><font color=\"red\" >Un total de $total_no_asignado prefijos no fueron asignados</font><br>";
					}
					echo "<br><br>------------------";
					

					mysql_close($db);		

					?>
					
					
					<br><br><br>
					<form>
					<input type="button" value="Volver"  onClick="location.href = 'tarifas_troncal.php?callcenter=<?php echo $callcenter; ?>&usuario_troncal=<?php echo $troncal; ?>'"  style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 
					</form>
					
					
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