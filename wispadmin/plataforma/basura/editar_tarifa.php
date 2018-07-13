<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$troncal=$_GET['usuario_troncal'];
$prefijo=$_GET['prefijo'];
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
	
	<SCRIPT LANGUAGE="Javascript">
	<!---
	function confirmar_eliminacion_tarifa()
	{
		troncal=document.form_cliente.form_troncal.value;	
		prefijo=document.form_cliente.form_prefijo.value;			
	   
		//centrar la ventana
		var w=250;
		var h=150;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
  
	    open('eliminar_tarifa_popup.php?troncal='+troncal+'&prefijo='+prefijo,'PopUp','directories=no, scrollbars=yes, resizable=no, height=150,width=250, width='+w+', height='+h+', top='+top+', left='+left);
	   
	   
	}
	
	
	function validar_texto(e){
		tecla = (document.all) ? e.keyCode : e.which;

		//Tecla de retroceso para borrar, siempre la permite
		if (tecla==8){
            return true;
		}
        
		// Patron de entrada, en este caso solo acepta numeros
		//patron =/[0-9]/;
		patron = /^([0-9])*[.]?[0-9]*$/;
    
		tecla_final = String.fromCharCode(tecla);
    
		return patron.test(tecla_final);
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
						$web_violacion="editar_tarifa.php";
						
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
						
					//conectamos a la BD para sacar los datos de grupo,descripcion,prefijo y coste,pk no vienen por post
					$sel = "select * from tarifas_prueba where usuario_troncal='$troncal' and prefijo='$prefijo'";					
					$query = mysql_query($sel) or die(mysql_error());
						
					while ($row = mysql_fetch_assoc($query)) 
					{
						$grupo=$row['grupo'];
						$descripcion=$row['descripcion'];
						$prefijo=$row['prefijo'];
						$coste=$row['coste'];

					}
						
					mysql_close($db);		
						

					?>		
					
					<h4 class="title">Editando <?php echo $prefijo.":".$descripcion;?></h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
					</div>
					
			
					
					
					<form action="editar_tarifa_efectivo.php" method="post" name="form_cliente" width="30%">
					<div align="center">
					
					</p>
					<p>

					<table width="80%">
					<caption>Datos Tarifa</caption>
					<tr>
					<td width="20%">Grupo: </td>
					<input type="hidden" align="center" size="53" name="form_grupo" readonly="yes" value="<?php echo $grupo;?>">
					<td width="30%" align="center"><?php echo $grupo;?></td>
					</tr>
					<tr>
					<td width="20%">Descripción: </td>
					<td width="30%" align="center"><?php echo $descripcion;?></td>
					</tr>
					<tr>
					<td width="20%">Prefijo: </td>
					
					<td width="30%" align="center"><?php echo $prefijo;?></td>
					<input type="hidden" size="53" name="form_prefijo_antiguo" value="<?php echo $prefijo;?>">
				    <input type="hidden" size="53" name="form_prefijo" value="<?php echo $prefijo;?>">
					</tr>
					<tr>
					<td width="20%">Coste(Euros): </td>
					<td width="30%" align="center"><input type="text" align="center" size="25" onkeypress="return validar_texto(event)" name="form_coste" value="<?php echo $coste;?>"></td>
					</tr>
					<input type="hidden" size="53" name="form_troncal" value="<?php echo $troncal;?>"></td>
					</table> 					
					
					</div>
					<p align="center">&nbsp;</p>
					<div align="center">
					<input type="button" value="Volver a Tarifas"  onClick="location.href = 'tarifas_troncal.php?callcenter=<?php echo $callcenter; ?>&usuario_troncal=<?php echo $troncal; ?>'"  style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> &nbsp;&nbsp;&nbsp; 					
					<input type="submit" value="Aplicar Cambios" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	&nbsp;&nbsp;&nbsp;
					<input type="button" onclick="confirmar_eliminacion_tarifa()" value="Eliminar Tarifa" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	

					</div>
					</form>

	             <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
	
	
				<?php } ?>
				
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