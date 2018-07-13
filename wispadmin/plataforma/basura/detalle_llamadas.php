<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$fechadesde=$_POST['fechadesde'];
$fechahasta=$_POST['fechahasta'];
$callcenter= $_POST['callcenter'];
$troncal = $_POST['usuario_troncal'];

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
	<title>Plataforma Facturaci&oacuten Grupo REQ</title>

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
	    <h1 id="logo"><a href="#" title="Plataforma Facturaci&oacuten">Plataforma Facturaci&oacuten</a></h1>
	</div>	<ul id="navigation">

	</ul>
	<!-- contenedor principal donde desplegar informacion -->
	<div id="main">
			<div id="content">
					<?php
					echo "<h4 class=\"title\">Detalle Llamadas para $troncal</h4>";	
					?>
					<p align="center">&nbsp;</p>
					<div align="center">
					
					<?php 
					
										//conectamos a  MYSQL
					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
					
					
					$usuario = $_SESSION['nombre_usuario'];
					

					echo "<table width='80% align='center'>";
					echo "<caption>Desde [$fechadesde] hasta 
[$fechahasta] </caption><thead><tr><td>Fecha</td><td>Origen</td><td>Destino</td><td>Segundos</td>
					<td>Coste</td><td>Tipo Fact.</td></thead><tbody>";		

					/*para el xls*/
					$tablaxls="<table width='100%'><caption>Llamadas $fechadesde - 
$fechahasta</caption><thead><tr><td>Fecha</td><td>Origen</td><td>Destino</td><td>Segundos</td>
					<td>Coste</td><td>Tipo Fact.</td></thead><tbody>";		

					$sel = "select calldate,clid,dst,segundos,coste from detallescoste where 
id_centralita='$callcenter' and usuario_troncal='$troncal' and calldate between '$fechadesde' and '$fechahasta'";
					$query = mysql_query($sel) or die(mysql_error());
						
					$colorfila=0;	
					while ($row = mysql_fetch_assoc($query)) 
					{
																		
						if($colorfila%2==0)
						{
							echo 
"<tr><td>".$row['calldate']."</td><td>".$row['clid']."</td><td>".$row['dst']."</td><td>".$row['segundos']."</td><td>".redondear_cinco_decimal($row['coste'])."</td><td>".$row['tipofacturacion']."</td></tr>";
					    }
						else
						{							
							echo "<tr 
class=\"odd\"><td>".$row['calldate']."</td><td>".$row['clid']."</td><td>".$row['dst']."</td><td>".$row['segundos']."</td><td>".redondear_cinco_decimal($row['coste'])."</td><td>".$row['tipofacturacion']."</td></tr>";
					    }
						$colorfila++;
						//para el xls
						
$tablaxls.="<tr><td>".$row['calldate']."</td><td>".$row['clid']."</td><td>".$row['dst']."</td><td>".$row['segundos']."</td><td>".redondear_cinco_decimal($row['coste'])."</td><td>".$row['tipofacturacion']."</td></tr>";
					    			
					}			

					mysql_close($db);		
						
					echo "</tbody>";
					echo "</table>";
					
					$tablaxls=str_replace(".",",",$tablaxls);	

					//asigno la variable tablaxls a una sesion para pillarla despues

					$_SESSION['tablazo']=$tablaxls;
					$_SESSION['nombrexls']=$fechadesde.$nbrcentralita;
					
					echo "<p align='right'><a href='generarexcel.php' title='Descargar en XLS' ><img src='images/csv_icon.gif' alt='Descargar en CSV' /></a></p>";
						
					
					?>
					
					<form action="troncales_callcenter.php?callcenter=<?php echo $callcenter;?>" method="post" name="form_centralita" width="30%">
					<div align="center">
					
					<input type="hidden" size="53" name="usuario_troncal" value="<?php echo $troncal;?>"></td>
					<input type="hidden" size="53" name="form_callcenter" value="<?php echo $callcenter;?>"></td>
					
					
					<input type="submit" value="Volver a servicios" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	&nbsp;&nbsp;&nbsp;

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
    </div>
	<!-- el footer de todalavida-->
	<div id="footer">
		<?php pie(); ?>
	</div>
</div>


</body>
</html>
