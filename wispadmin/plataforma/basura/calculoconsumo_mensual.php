<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$mes=$_POST['meses'];
$ano=$_POST['ano'];
$cif=$_POST['usuario_consulta'];


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
			
					<h4 class="title">Consumo mensual</h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
					
					
					<?php
					
					
						//conectamos a  MYSQL

						$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
						// Seleccionamos la Base de datos
						mysql_select_db($nombre_bd) or die(mysql_error());
					
						//Asignando variables para realizar los calculos con respecto el mes.

					
					//Mostrar el nombre del mes 
					if($mes)
					{
						if($mes==01)
						$valormes=Enero;
						if($mes==02)
						$valormes=Febrero;
						if($mes==03)
						$valormes=Marzo;
						if($mes==04)
						$valormes=Abril;
						if($mes==05)
						$valormes=Mayo;
						if($mes==06)
						$valormes=Junio;
						if($mes==07)
						$valormes=Julio;
						if($mes=='08')
						$valormes=Agosto;
						if($mes=='09')
						$valormes=Septiembre;
						if($mes==10)
						$valormes=Octubre;
						if($mes==11)
						$valormes=Noviembre;
						if($mes==12)
						$valormes=Diciembre;
						
					}
							
							
							
					//se crea la tabla
					if($mes=='00')
					{

					$mesinicio="$ano"."-01"."-01";
					$mesfin="$ano"."-12"."-31";						
					
					echo "<table width='100%'>";
					echo "<caption>Consumo mensual: $cif</caption><thead><tr><td>Troncal</td><td>Mes</td><td>Nº Llamadas</td><td>Consumo(Eur.)</td></thead><tbody>";
					$colorfila=0;	
					
					//conectamos a  MYSQL
						
					$sel = "select * from gestioncdr.consumo_mensual
					where usuario_troncal in (select usuario_troncal from troncales where id_centralita in ( select id_centralita from
					centralitas where cif_user='$cif')) and anno=$ano";
					$query = mysql_query($sel) or die(mysql_error());

						while ($row = mysql_fetch_assoc($query)) 
						{
			
						if($colorfila%2==0)
						{
							echo "<tr class=\"odd\" ><td>".$row['usuario_troncal']."</td><td>".$row['mes']."</td><td>".$row['numerollamadas']."</td><td>".$row['coste']."</td></tr>";
					    }
						else
						{
							
						   echo "<tr><td>".$row['usuario_troncal']."</td><td>".$row['mes']."</td><td>".$row['numerollamadas']."</td><td>".$row['coste']."</td></tr>";
					    }	
						$colorfila++;
						}
					}
					else
					{
					
					echo "<table width='100%'>";
					echo "<caption>Consumo mensual: $cif</caption><thead><tr><td>Troncal</td><td>Mes</td><td>Nº Llamadas</td><td>Consumo(Eur.)</td></thead><tbody>";
				
					$colorfila=0;	
					//conectamos a  MYSQL
					$año=$ano."-";
					$mesinicio="$año"."$mes"."-01";
					$mesfin="$año"."$mes"."-31";	
					
					$sel = "select * from gestioncdr.consumo_mensual
					where usuario_troncal in (select usuario_troncal from troncales where id_centralita in ( select id_centralita from
					centralitas where cif_user='$cif')) and anno=$ano and mes=$valormes";
					
					
					
					$query = mysql_query($sel) or die(mysql_error());

						while ($row = mysql_fetch_assoc($query)) 
						{
							if($colorfila%2==0)
							{
								echo "<tr class=\"odd\" ><td>".$row['usuario_troncal']."</td><td>".$row['mes']."</td><td>".$row['numerollamadas']."</td><td>".$row['coste']."</td></tr>";
							}
							else
							{
							
								echo "<tr><td>".$row['usuario_troncal']."</td><td>".$row['mes']."</td><td>".$row['numerollamadas']."</td><td>".$row['coste']."</td></tr>";
							}	
							$colorfila++;
							}
					
					}
					
					
						
					
					mysql_close($db);		
						
					//cierro la tabla	
					echo "</tbody>";
					echo "</table>";
					echo "<br><p><div align=\"right\">";
					//echo "Del $mesinicio al $mesfin";
					echo "</div></p>";
			
					?>
					
					<form>
					<input type="button" value="Volver Clientes"  onClick="location.href = 'clientes.php'" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 
					</form>
					
					</div>
				
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

	<!-- el footer de todalavida-->
		<div id="footer">
		<?php pie(); ?>
	    </div>
</div>

</body>
</html>