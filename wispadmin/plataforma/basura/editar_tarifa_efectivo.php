<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$troncal=$_POST['form_troncal'];
$descripcion=$_POST['form_descripcion'];
$prefijo=$_POST['form_prefijo'];
//atencion prefijo es clave primaria tengo que almacenar el prefijo antiguo para la actualizacion
$prefijo_antiguo=$_POST['form_prefijo_antiguo'];
$coste=$_POST['form_coste'];

$cif = cif_superusuario($usuario);	

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
					 
						//conectamos a  MYSQL


					$prefijo_proveedor="";
					$coste_proveedor="";
					$errorcito="";
					
					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
					
						
					//primero comprobamos que la tarifa este autorizada en la del proveedor
					//IMPORTANTE aunque este caso no se va a dar nunca ya que el prefijo no es modificable, lo dejo total...
				
					$sel="select prefijo,coste from tarifasminimassuperusuario_prueba where cif_super='$cif' and prefijo='$prefijo'";
					
					//echo "consulta: $sel";

					$query = mysql_query($sel) or die(mysql_error());
				
					
					while ($row = mysql_fetch_assoc($query)) 
					{								
						$prefijo_proveedor=$row['prefijo'];
						$coste_proveedor=$row['coste'];
					}
					
					/*echo "obtenido prefijo:$prefijo_proveedor<br>";
					echo "obtenido coste:$coste_proveedor<br>";
					echo "coste pasado= $coste";*/
					
					if($prefijo_proveedor=="")
					{
					   $errorcito="Dicha prefijo no esta autorizado en la tabla de redistribución del proveedor";
					   $errorcito2="Solicite su autorización y presupuestación al responsable de la plataforma";
					   
					}else if( $coste < $coste_proveedor)//el prefijo concuerda, ahora el coste del proveedor tiene que ser menor que el coste k se pasa
					{
					   $errorcito="El coste establecido para el cliente no puede ser inferior al establecido para el revendedor por la plataforma";
					   $errorcito2="En este caso el coste por minuto para el destino $prefijo ha de ser superior o igual a $coste_proveedor (consulte tabla tarifas redistribucion)";
					}
					else //si todo ok => modifico la tarifa del cliente
					{
					
							//antes de esto miro que el prefijo no este duplicado y ya tenga el cliente una tarifa con ese prefijo
					
							/*$sel= "select prefijo from tarifas_prueba where usuario_troncal='$troncal' and prefijo='$prefijo'";
					
							$query = mysql_query($sel) or die(mysql_error());
				
					
							while ($row = mysql_fetch_assoc($query)) 
							{								
								$prefijo_existente=$row['prefijo'];
							}
					
							if($prefijo_existente==$prefijo) //ya existia esa tarifa en esa troncal => error
							{
								$errorcito="Ya existe una tarifa asociada a este prefijo";
								$errorcito2="Inserte un prefijo diferente o edite la tarifa existente";
					
							}
							else
							{*/
								$sel = "update tarifas_prueba set coste = '$coste' where usuario_troncal='$troncal' and prefijo='$prefijo_antiguo'";
				
								$query = mysql_query($sel) or die(mysql_error());
					
							//}					
						

						
					}	
					
					
					//saco el id de la centralita asociada para la redireccion
					
					$sel = "select id_centralita from centralitas where id_centralita=(select id_centralita from troncales where usuario_troncal='$troncal')";
					$query = mysql_query($sel) or die(mysql_error());
						
					while ($row = mysql_fetch_assoc($query)) 
					{
						$callcenter=$row['id_centralita'];
					}	
					
					mysql_close($db);	

					?>
					
					<h4 class="title">Modificado <?php echo $cif;?></h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
					<?php
					
					if($errorcito=="")
					{
					echo"
					
					Tarifa modificada con exito.<br><br><br>
					<form type=\"GET\" action=\"tarifas_troncal.php?callcenter=$callcenter&usuario_troncal=$troncal\">
					<p>&nbsp;</p>
					<input type=\"button\" value=\"Volver a Tarifas\"  onClick=\"location.href = 'tarifas_troncal.php?callcenter=$callcenter&usuario_troncal=$troncal'\"  style=\" width:90;height:90; background-image:url(./images/bg_button.gif)\"> 
					</form>";
					}
					else
					{
					echo"
					Error al modificar la tarifa.<br><br><br>
					$errorcito<br>
					$errorcito2
					<form type=\"GET\" action=\"tarifas_troncal.php?callcenter=$callcenter&usuario_troncal=$troncal\">
					<p>&nbsp;</p>
					<input type=\"button\" value=\"Volver a Tarifas\"  onClick=\"location.href = 'tarifas_troncal.php?callcenter=$callcenter&usuario_troncal=$troncal'\"  style=\" width:90;height:90; background-image:url(./images/bg_button.gif)\"> 
					</form>";
					}
					?>
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