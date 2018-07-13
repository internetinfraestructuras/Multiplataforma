<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
//$cif_cliente=$_GET['cliente'];

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
		
		function validar_dni(e){
		tecla = (document.all) ? e.keyCode : e.which;

		//Tecla de retroceso para borrar, siempre la permite
		if (tecla==8){
            return true;
		}
        
		// Patron de entrada, en este caso solo acepta numeros y letras
		//nada de guiones chungos 
		patron = /^([A-Z])*[a-z]*([0-9])*$/;
    
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
			
					<p align="center">&nbsp;</p>


					
					<h4 class="title">Alta Grupo de Recarga</h4>	
					<p align="center">&nbsp;</p>

					
					<form action="alta_grupoderecarga_efectivo.php" method="post" name="form_cliente" width="30%">
					<div align="center">
					
					</p>
					<p>

					<table  border="1" cellpadding="0" cellspacing="0">
					<caption>Alta Grupo de Recarga</caption>
					<tr>
					<td width="20%">Nombre: </td>
					<td width="30%" align="center"><input type="text" size="53" name="form_nombre" ></td>
					</tr>
					<tr>
					<td width="20%">Importe: </td>
					<td width="30%" align="center"><input type="text" size="53" name="form_importe"></td>
					</tr>
					<tr>
					<td width="20%">Acumulable: </td>
					<td width="30%" align="center">
					<select  name="form_acumulable" selected="NO">
					<option value="NO">NO</option>
					<option value="SI">SI</option>
					</select>
					</td>
					</tr>					
					<tr>
					<td width="20%">Color: </td>
					<td width="30%" align="center"><input id="background-color" name="background-color" type="color" /> </td>
					</tr>
				
					
					</table> 	
					<p>
					&nbsp;
					</p>
					<p>
					NOTA: Acumulable=SI indica que la recarga se efectuará siempre añadiendo el saldo de la recarga
					mensual al ya<br> existente para el cliente. Acumulable=NO indica que mensualmente el saldo del cliente
					será establecido al importe<br> del grupo de recarga independientemente del ya existente en su cuenta
					</p>
					</div>
					<p align="center">&nbsp;</p>
					<div align="center">
					
					<input type="submit" value="Aceptar" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	&nbsp;&nbsp;&nbsp;

					</div>
					</form>

				

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
