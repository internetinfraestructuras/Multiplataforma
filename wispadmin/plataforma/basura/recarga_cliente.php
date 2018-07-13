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
	
	<SCRIPT LANGUAGE="Javascript">
	<!---
	function recargar(masteruser)
	{
		cif=document.form_cliente_recarga.form_cif.value;
		importe=document.form_cliente_recarga.form_incremento.value;
		
		//centrar la ventana
		var w=500;
		var h=300;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
  		
	   open('recarga_popup.php?super='+masteruser+'&cif='+cif+'&importe='+importe,'PopUp','directories=no, scrollbars=yes, resizable=no, height=300,width=500, width='+w+', height='+h+', top='+top+', left='+left);
	   
	   
	}
	
	function establecer_a_0(masteruser)
	{
		cif=document.form_cliente_recarga.form_cif.value;
		importe=document.form_cliente_recarga.form_incremento.value;
		
		//centrar la ventana
		var w=500;
		var h=150;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
  		
	   open('a_cero_popup.php?super='+masteruser+'&cif='+cif,'PopUp','directories=no, scrollbars=yes, resizable=no, height=150,width=400, width='+w+', height='+h+', top='+top+', left='+left);
	   
	   
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
						$seccion= "recarga_cliente.php";
						
						//notifico la violacion
						$var = " /usr/sendEmail/sendEmail -f plataforma.prepago@gmail.com -t paco.perez@gruporeq.com -s smtp.gmail.com -u Plataforma REQ: Intento violacion por metodo GET   -v -o tls=yes -xu plataforma.prepago -xp gruporeq1430 -m EL SuperUsuario: $usuario, intento acceder al callcenter del usuario=$cif_usuario que realmente es de $superuser_propietario_legitimo en la seccion $seccion desde la ip $ip_acceso";
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
						$saldo=$row['saldo'];
						$tipofacturacion=$row['tipofacturacion'];
					}
						
					$subtipo = substr($tipofacturacion, 0,4);	
					if($subtipo=="MPRE")
					{
					  $frasesaldo="Minutos Actuales";
					  $frasesaldo2="Incrementar Minutos en";
					}
					else if ($subtipo=="SPRE")
					{
					  $frasesaldo="Saldo Actual (Euros): ";
					  $frasesaldo2="Incrementar Saldo en (Euros)";
					}
					

						
					mysql_close($db);		
						

					?>
					
					<h4 class="title">Recarga Cliente <?php echo $cif_cliente;?></h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
					</div>
					
					<form  method="post" name="form_cliente_recarga" width="30%">
					<div align="center">
					
					</p>
					<p>
					
					<!-- FALTAN MEDIDAS DE SI NO TIENE SALDO SUFICIENTE PARA RECARGAR AL CLIENTE , si se mete un numero y no texto-->
					
					<table >
					<caption>Datos</caption>
					<tr>
					<td width="20%">Cif: </td>
					<td width="30%" align="center"><?php echo $cif_cliente;?></td>
					<input type="hidden" size="20" name="form_cif" value="<?php echo $cif_cliente;?>">
					</tr>
					<tr>
					<td width="20%">Nombre: </td>
					<td width="30%" align="center"><?php echo $nombre_cliente;?></td>
					</tr>
					<tr>

					<td width="20%"><?php echo $frasesaldo;?></td>
					<td width="30%" align="center"><?php echo $saldo;?></td>
					</tr>
					<tr>
					<td width="20%"><?php echo $frasesaldo2;?></td>
					<td width="30%" align="center"><input type="text" size="20" name="form_incremento" onkeypress="return validar_texto(event)" value=""></td>
					</tr>
					</table> 	
					</form>
					
					
					</div>
					<p align="center">&nbsp;</p>
					<div align="center">
					<form>
					
					<input type="button" value="Recargar" onclick="recargar('<?php echo $usuario;?>')" style=" width:90;height:90; background-image:url(./images/bg_button_green.gif)"> 
					
					<input type="button" value="Establecer a 0" onclick="establecer_a_0('<?php echo $usuario;?>')" style=" width:90;height:90; background-image:url(./images/bg_button_red.gif)"> 
					
					
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