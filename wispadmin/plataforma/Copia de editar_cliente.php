<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$idCliente=$_GET['idcliente'];
$name=$_GET['name'];
$service=$_GET['service'];
$password=$_GET['password'];
$profile=$_GET['profile'];
$ip=$_GET['ip'];
$comment=$_GET['comment'];



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
		cif=document.form_cliente.form_cif.value;		
		
		//centrar la ventana
		var w=250;
		var h=150;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
 
		
	   open('eliminar_cliente_popup.php?super='+masteruser+'&cif='+cif,'PopUp','directories=no, scrollbars=yes, resizable=no, height=150,width=250, width='+w+', height='+h+', top='+top+', left='+left);
	   
	   
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
						
						
					$sel = "select * from usuarios where cif='$cif_cliente'";
					$query = mysql_query($sel) or die(mysql_error());
						
					while ($row = mysql_fetch_assoc($query)) 
					{
					
						$nombre_cliente=$row['nombre'];
						$email_cliente=$row['email'];
						$direccion_cliente=$row['direccion'];
						$usuario_plataforma_cliente=$row['usuario'];
						$password_plataforma_cliente=$row['password'];
						$umbral_alerta_cliente=$row['umbralalerta'];
						$tipofacturacion=$row['tipofacturacion'];
						$grupoderecarga=$row['grupoderecarga'];
					}
						
					mysql_close($db);		
						

					?>
					
					<h4 class="title">Editando <?php echo $cif_cliente;?></h4>	
					<p align="center">&nbsp;</p>
					
					<form action="editar_cliente_efectivo.php" method="post" name="form_cliente" width="30%">
					<div align="center">
					

					<table  border="1" cellpadding="0" cellspacing="0">
					<caption>Datos Cliente</caption>
					<tr>
					<td width="20%">Cif: </td>
					<td width="30%" align="center"><input type="text" readonly="readonly" align="center" size="53" name="form_cif" value="<?php echo $cif_cliente;?>"></td>
					</tr>
					<tr>
					<td width="20%">Nombre: </td>
					<td width="30%" align="center"><input type="text" size="53" name="form_nombre" value="<?php echo $nombre_cliente;?>"></td>
					</tr>
					<tr>
					<td width="20%">Email: </td>
					<td width="30%" align="center"><input type="text" size="53" name="form_email" value="<?php echo $email_cliente;?>"></td>
					</tr>
					<tr>
					<td width="20%">Dirección: </td>
					<td width="30%" align="center"><input type="text" size="53" name="form_direccion" value="<?php echo $direccion_cliente;?>"></td>
					</tr>
					<tr>
					<td width="20%">Usuario Plataforma: </td>
					<td width="30%" align="center"><input type="text" size="53" name="form_usuario" value="<?php echo $usuario_plataforma_cliente;?>"></td>
					</tr>
					<tr>
					<td width="20%">Password Plataforma: </td>
					<td width="30%" align="center"><input type="text" size="53" name="form_password" value="<?php echo $password_plataforma_cliente;?>"></td>
					</tr>
					<tr>
					<td width="20%">Umbral Alerta (Euros): </td>
					<td width="30%" align="center"><input type="text" size="53" name="form_umbralalerta" onkeypress="return validar_texto(event)" value="<?php echo $umbral_alerta_cliente;?>"></td>
					</tr>
					<tr>
					<td width="20%">Tipo de Facturación: </td>
					<td width="30%" align="center">

					<select  name="form_tipodefacturacion">
					<?php
						  $subtipo = substr($tipofacturacion, 0,4);						
					   if($subtipo=="SPRE")
					      echo " <option value=\"SPRE\" selected='selected'>Euros Prepago</option>
								 <option value=\"MPRE\">Minutos Prepago</option>";
					   else if($subtipo=="MPRE")
					      echo " <option value=\"SPRE\" >Euros Prepago</option>
								 <option value=\"MPRE\" selected='selected'>Minutos Prepago</option>";
					?>
					</select>
					</td>
					</tr>
					<tr>
					<td width="20%">Grupo de Recarga: </td>
					<td width="30%" align="center">
					
					<?php 

					if($grupoderecarga=="NULL" || $grupoderecarga==""){
							echo "<select  name='form_grupoderecarga' selected='Ninguno'>";
						  }
						  else{
							echo "<select  name='form_grupoderecarga' selected='$grupoderecarga'>";
						  }
						  
						  
						//listamos los grupos de recarga para este superusuario
						$usu=cif_superusuario($usuario);	
						$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
						// Seleccionamos la Base de datos
						mysql_select_db($nombre_bd) or die(mysql_error());
						$sel = "select nombregrupo,color from gruposderecarga where cif_super='$usu' order by nombregrupo";		 						
				
						$query = mysql_query($sel) or die(mysql_error());

						while ($row = mysql_fetch_assoc($query)) 
						{
							$nombregrupo=$row['nombregrupo'];
							$color=$row['color'];
							
							if($grupoderecarga ==$nombregrupo)
								echo "<option value='$nombregrupo' style='background:$color' selected='selected'>$nombregrupo</option>";
							else
								echo "<option value='$nombregrupo' style='background:$color'>$nombregrupo</option>";
						}
						//añadimos el nulo fuera de php
						if($grupoderecarga=="NULL" || $grupoderecarga==""){
							echo "<option value='NULL' selected='selected'>--Ninguno--</option>";
						}
						else
						echo "<option value='NULL'>--Ninguno--</option>";
					?>			
						
					</select>
					</td>
					</tr>	

					
					</table> 					
					
					
					</div>
					<p align="center">&nbsp;</p>
					<div align="center">
					<input type="button" value="Volver a Clientes"  onClick="location.href = 'clientes.php'"  style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	&nbsp;&nbsp;&nbsp;				
					<input type="submit" value="Aplicar Cambios" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	&nbsp;&nbsp;&nbsp;
					<input type="button" onclick="confirmar_eliminacion('<?php echo $usuario;?>')" value="Eliminar Cliente" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	

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