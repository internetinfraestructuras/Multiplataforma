<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$cif_cliente=$_GET['cliente'];

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
			
			        
					
					<h4 class="title"><?php echo "Cliente: $cif_cliente";?></h4>	
					<p align="center">&nbsp;</p>
					<div align="center"> 
					<?php
					
					//superbloque verificacion legitimidad
					//como el id del cliente viene por GET, si un listo lo cambia puede acceder a los clientes
					//de otra peña asi que verifico que el cliente con ese cif es propietario del tio que cursa la sesion
					
					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
						
						
					$sel = "select superusuario from superusuarios where cif_superuser = (select cif_super
					from usuarios where cif = '$cif_cliente')";
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
						
						//notifico la violacion
						$var = " /usr/sendEmail/sendEmail -f plataforma.prepago@gmail.com -t paco.perez@gruporeq.com -s smtp.gmail.com -u Plataforma REQ: Intento violacion por metodo GET   -v -o tls=yes -xu plataforma.prepago -xp gruporeq1430 -m EL SuperUsuario: $usuario, intento acceder al callcenter del usuario=$cif_usuario que realmente es de $superuser_propietario_legitimo en la seccion callcenters.php desde la ip $ip_acceso";
						exec($var);
						
						//cierro sesiones
						session_start();
						session_destroy();
					}
					else
					{					
					
					
					
					echo "<table width='80%'>";
					echo "<caption>Call Centers</caption><thead><tr><td>ID</td><td>Nombre</td><td>IP Pública</td><td>Troncales</td></thead><tbody>";

					$colorfila=0;	
					//conectamos a  MYSQL

					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
						
						
					$sel = "select * from centralitas where cif_user='$cif_cliente'";
					$query = mysql_query($sel) or die(mysql_error());
						
					while ($row = mysql_fetch_assoc($query)) 
					{
					
						if($row['habilitado']=='no')
						{ 
						  $habi="disabled=\"true\" onclick=\"return false\"";
						  $colordeshabilitado= "class=\"deshabilitado\"";
						  $mostrarleyenda=1;
						}
						else if($row['habilitado']=='ptron')
						{
						  $colordeshabilitado= "class=\"bloqueado\"";
						  $mostrarleyenda_bloq=1;	
						}
						else
						{
						   $habi="";
						   $colordeshabilitado="";

						}					
					
											
						if($colorfila%2==0)
						{
							echo "<tr $colordeshabilitado ><td>".$row['id_centralita']."</td><td><a title='Editar'  href=\"editar_callcenter.php?callcenter=".$row['id_centralita']."\">".$row['nombre']."</a></td><td>".$row['ip_publica']."</td><td><a title='Ver Troncales'  href=\"troncales_callcenter.php?callcenter=".$row['id_centralita']."\">Troncales Téc.</a></td></tr>";
					    }
						else
						{							
							echo "<tr $colordeshabilitado ><td>".$row['id_centralita']."</td><td><a title='Editar'  href=\"editar_callcenter.php?callcenter=".$row['id_centralita']."\">".$row['nombre']."</a></td><td>".$row['ip_publica']."</td><td><a title='Ver Troncales'  href=\"troncales_callcenter.php?callcenter=".$row['id_centralita']."\">Troncales Téc.</a></td></tr>";
						}	
						$colorfila++;
					}
						
					
					mysql_close($db);		
						
					echo "</tbody>";
					echo "</table>";
						
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
					
					   echo "	<p>&nbsp;</p>	
					   
					   <div align='right'>
					
					   <img src='amarillo.jpg'>&nbsp;</img>&nbsp;&nbsp;Posee Troncales en trámite&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							
					   </div>";	
					   		   
					}
					
					?>		
															<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
					</div>
					
					<form action="alta_callcenter.php" method="post" name="form_centralita" width="30%">
					<div align="center">
					
					<input type="hidden" size="53" name="cif_cliente" value="<?php echo $cif_cliente;?>"></td>
					
					<input type="button" value="Volver a Clientes"  onClick="location.href = 'clientes.php'"  style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 
					<input type="submit" value="Añadir Call Center a este Cliente" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	&nbsp;&nbsp;&nbsp;

					</div>
					</form>
					
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