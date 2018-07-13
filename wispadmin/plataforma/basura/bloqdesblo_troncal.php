<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$callcenter= $_GET['callcenter'];
$troncal = $_GET['usuario_troncal'];


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
	
	<script>
	function bloquear(valor)
	{ 
		document.form_cliente.form_habilitado.value=valor;	
	}
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

					<h4 class="title"><?php echo "Bloqueo Troncal $troncal";?></h4>			
			        <div align="center"> 
					<?php
					
					//superbloque verificacion legitimidad
					//como el id del cliente viene por GET, si un listo lo cambia puede acceder a los clientes
					//de otra peña asi que verifico que el cliente con ese cif es propietario del tio que cursa la sesion
					
					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
						
						
					$sel = "select superusuario from superusuarios where cif_superuser = (select cif_super
					from usuarios where cif = (select cif_user from centralitas where id_centralita='$callcenter'))";
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
						$web_violacion="bloqdesblo_troncal.php";
						
						//notifico la violacion
						$var = " /usr/sendEmail/sendEmail -f plataforma.prepago@gmail.com -t paco.perez@gruporeq.com -s smtp.gmail.com -u Plataforma REQ: Intento violacion por metodo GET   -v -o tls=yes -xu plataforma.prepago -xp gruporeq1430 -m EL SuperUsuario: $usuario, intento acceder al callcenter del usuario=$cif_usuario que realmente es de $superuser_propietario_legitimo en la seccion $web_violacion desde la ip $ip_acceso";
						exec($var);
						
						//cierro sesiones
						session_start();
						session_destroy();
					}
					else	
					{					
					
					
					//conectamos a  MYSQL

					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
						
						
					$sel = "select habilitado from troncales where id_centralita='$callcenter' and usuario_troncal='$troncal'";
					$query = mysql_query($sel) or die(mysql_error());
					
					$ret = mysql_fetch_array($query);  
					$habilitado=$ret['habilitado']; 
					
					mysql_close($db);
					
					
					?>		
															<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
					</div>
					
					<form action="bloqdesblo_efectivo.php" method="post" name="form_cliente" width="30%">
					<div align="center">
					
					<input type="hidden" size="53" name="usuario_troncal" value="<?php echo $troncal;?>"></td>
					<input type="hidden" size="53" name="form_callcenter" value="<?php echo $callcenter;?>"></td>
					
					Bloqueado&nbsp;

					<input type="hidden" name="form_habilitado" value="<?php echo $habilitado;?>">
					
					<?php 
					if ($habilitado=='no')
					{
					  echo" <input name=\"opcion\" checked=\"true\" type=\"radio\" onClick=\"bloquear('no')\" value=\"SI\">Si&nbsp;&nbsp;&nbsp;
					        <input name=\"opcion\" type=\"radio\" onClick=\"bloquear('si')\" value=\"NO\">No";
							
							//si bloqueo => habilitado=bloq si desbloqueo => habilitado=si
					
					}
					else
					{
					  echo" <input name=\"opcion\"  type=\"radio\" onClick=\"bloquear('no')\" value=\"SI\">Si&nbsp;&nbsp;&nbsp;
					        <input name=\"opcion\" checked=\"true\" type=\"radio\" onClick=\"bloquear('si')\" value=\"NO\">No";
					}		
					?>
					<p>
					<br>
					<br>
					<input type="submit" value="Aplicar Cambios" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	&nbsp;&nbsp;&nbsp;
					</p>

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
</div>

</body>
</html>