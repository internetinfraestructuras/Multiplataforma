<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$callcenter=$_GET['callcenter'];

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
	
	</script>

	<SCRIPT LANGUAGE="Javascript">
	<!---
	function confirmar_eliminacion_callcenter(callcenter)
	{
		callcenter=document.form_cliente.form_callcenter.value;		
		//alert('hola '+cif+'importe '+importe+'superuser= '+masteruser);
	   //alert('hola');
	   
		//centrar la ventana
		var w=250;
		var h=150;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);


	   open('eliminar_callcenter_popup.php?callcenter='+callcenter,'PopUp','directories=no, scrollbars=yes, resizable=no, height=150,width=250, width='+w+', height='+h+', top='+top+', left='+left);
	   
	   
	}
	// --->
	</SCRIPT>

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
						$web_violacion="editar_callcenter.php";
						
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
						
						
					$sel = "select * from centralitas where id_centralita='$callcenter'";
					$query = mysql_query($sel) or die(mysql_error());
						
					while ($row = mysql_fetch_assoc($query)) 
					{
					
						$nombre=$row['nombre'];
						$ip_publica=$row['ip_publica'];

					}
						
					mysql_close($db);		
						

					?>
					
					<h4 class="title">Editando <?php echo $nombre;?></h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
					</div>
					
					<form action="editar_callcenter_efectivo.php" method="post" name="form_cliente" width="30%">
					<div align="center">
					
					</p>
					<p>

					<table  border="1" cellpadding="0" cellspacing="0">
					<caption>CallCenter</caption>
					<tr>
					<td width="20%">Nombre: </td>
					<td width="30%" align="center"><input type="text" align="center" size="53" name="form_nombre" value="<?php echo $nombre;?>"></td>
					</tr>
					<tr>
					<td width="20%">IP Publica: </td>
					<td width="30%" align="center"><input type="text" size="53" name="form_ippublica" value="<?php echo $ip_publica;?>"></td>
					</tr>
					<input type="hidden" size="53" name="form_callcenter" value="<?php echo $callcenter;?>"></td>
					</table> 					
					
					</div>
					<p align="center">&nbsp;</p>
					<div align="center">
					
					<input type="submit" value="Aplicar Cambios" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	&nbsp;&nbsp;&nbsp;
					<input type="button" onclick="confirmar_eliminacion_callcenter('<?php echo $callcenter;?>')" value="Eliminar Call Center" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	

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