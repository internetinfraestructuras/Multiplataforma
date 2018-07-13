<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$nombregrupo=$_GET['grupoderecarga'];
$cif_super_get=$_GET['cif_super'];

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
	function confirmar_eliminacion(masteruser)
	{
		supercif=document.form_cliente.cif_super_get.value;		
		
		//centrar la ventana
		var w=250;
		var h=150;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
 
		
	   open('eliminar_grupoderecarga_popup.php?gruporecarga='+masteruser+'&cif_super='+supercif,'PopUp','directories=no, scrollbars=yes, resizable=no, height=150,width=250, width='+w+', height='+h+', top='+top+', left='+left);
	   
	   
	}
	
	function bloquear(valor)
	{ 
		document.form_cliente.form_habilitado.value=valor;	
	}
	
	
	function validar_texto(e)
	{
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
			
			         
					<?php
					
					//superbloque verificacion legitimidad
					//como el id del cliente viene por GET, si un listo lo cambia puede acceder a los clientes
					//de otra peña asi que verifico que el cliente con ese cif es propietario del tio que cursa la sesion
					
					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
						
						
					$sel = "select superusuario from superusuarios where cif_superuser = (select cif_super
					from gruposderecarga where cif_super = '$cif_super_get' and nombregrupo='$nombregrupo')";
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
						$var = " /usr/sendEmail/sendEmail -f plataforma.prepago@gmail.com -t paco.perez@gruporeq.com -s smtp.gmail.com -u Plataforma REQ: Intento violacion por metodo GET   -v -o tls=yes -xu plataforma.prepago -xp gruporeq1430 -m EL SuperUsuario: $usuario, intento acceder al usuario=$cif_usuario que realmente es de $superuser_propietario_legitimo en la seccion editar_cliente.php desde la ip $ip_acceso";
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
						
						
					$sel = "select * from gruposderecarga where nombregrupo='$nombregrupo'";
					$query = mysql_query($sel) or die(mysql_error());
						
					while ($row = mysql_fetch_assoc($query)) 
					{
					
						$importe=$row['importerecarga'];
						$acumulable=$row['acumulable'];
						$color=$row['color'];

					}
						
					mysql_close($db);		
						

					?>
					
					<h4 class="title">Editando <?php echo $nombregrupo;?></h4>	
					<p align="center">&nbsp;</p>
					
					<form action="editar_grupoderecarga_efectivo.php" method="post" name="form_cliente" width="30%">
					<input type="hidden" name="cif_super_get" id="cif_super_get" value="<?php echo $cif_super_get; ?>">
					<div align="center">
					

					<table  border="1" cellpadding="0" cellspacing="0">
					<caption>Grupo de Recarga</caption>
					<tr>
					<td width="20%">Nombre: </td>
					<td width="30%" align="center"><input type="text" size="53" name="form_nombre" value="<?php echo $nombregrupo;?>" ></td>
					<input type="hidden" size="53" name="form_nombre_antiguo" value="<?php echo $nombregrupo;?>" >
					</tr>
					<tr>
					<td width="20%">Importe: </td>
					<td width="30%" align="center"><input type="text" size="53" name="form_importe" value="<?php echo $importe;?>"></td>
					</tr>
					<tr>
					<td width="20%">Acumulable: </td>
					<td>
					<select  name="form_acumulable">
					<?php				
					   if($acumulable=="SI")
					      echo " <option value=\"SI\" selected='selected'>SI</option>
								 <option value=\"NO\">NO</option>";
					   else if($acumulable=="NO")
					      echo " <option value=\"SI\" >SI</option>
								 <option value=\"NO\" selected='selected'>NO</option>";
					?>
					</select>
					</td>
					</tr>					
					<tr>
					<td width="20%">Color: </td>
					<td width="30%" align="center"><input id="background-color" name="background-color" type="color" value="<?php echo $color;?>"/> </td>
					</tr>
				
					
					</table> 	
				
					
					
					</div>
					<p align="center">&nbsp;</p>
					<div align="center">
					<input type="button" value="Volver a Grupos de Recarga"  onClick="location.href = 'gruposderecarga.php'"  style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	&nbsp;&nbsp;&nbsp;				
					<input type="submit" value="Aplicar Cambios" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	&nbsp;&nbsp;&nbsp;
					<input type="button" onclick="javascript:confirmar_eliminacion('<?php echo $nombregrupo;?>')" value="Eliminar Grupo de Recarga" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	

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