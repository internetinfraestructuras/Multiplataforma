<?php

include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$form_nombre=$_POST['form_nombre'];
$form_importe=$_POST['form_importe'];
$form_importe2=$_POST['form_importe'];
$form_acumulable=$_POST['form_acumulable'];
$color=$_POST['background-color'];

if(strpos($form_importe,'.'))
{
	//es un float
	$form_importe= (float)$form_importe;

}
else if(strpos($form_importe,','))
{
	//es un float
	$form_importe= (float)$form_importe;

}else{
	//debe ser un int
	//PERO ANTES MIRO SI ES UN NUMERO
	if(is_numeric($form_importe))
			$form_importe= (int)$form_importe;
	//else => al carajo petara abajo
}



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
			         
					<?php
					  
					$cif_super=cif_superusuario($usuario);	  
						
					//verifico que todo viene relleno
					$error=0;
					$cadena_error="Error:<br><p>&nbsp;</p><p>&nbsp;</p>";

					if($form_nombre=="")
					{
						$error=1;
						$cadena_error= $cadena_error."Nombre de grupo incorrecto<br>";
					}
					if($form_importe=="")
					{
						$error=1;
						$cadena_error= $cadena_error ."Importe incorrecto<br>";
					}
					if(!is_int($form_importe))
					{
						echo "no es enteroooo";
						//si no es entero compruebo que sea un float
						if(!is_float($form_importe)){
							//si no es float => error ni entero ni float
							echo "nboi es float";
							$error=1;
							$cadena_error= $cadena_error ."Debe introducir un importe válido<br>";
						}
					}
					
						
					if($error==0)
					{
					//conectamos a  MYSQL

					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
						
											
				/*	echo 
					$form_nombre."<br>";
echo $form_importe."<br>";
echo $form_acumulable."<br>";
echo $color."<br>";*/

					$sel = "insert into 
					gruposderecarga (cif_super,nombregrupo,importerecarga,acumulable,color) 
					VALUES ('$cif_super', '$form_nombre','$form_importe2','$form_acumulable','$color')";
				
				
					$query = mysql_query($sel) or die(mysql_error());
						
					mysql_close($db);		
						
					

					?>
					
					<h4 class="title">Alta Grupo de Recarga</h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
				    <table  border="1" cellpadding="0" cellspacing="0">
					<caption>Alta Grupo de Recarga: <?php echo $form_nombre;?>.</caption>
					</table>
					
					Almacenados grupo de recarga.<br><br><br>
				

				<?php 	
					}
					else
					{
					?>
					
					<h4 class="title">Alta Grupo de Recarga - Error!</h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
					<?php
					
					echo $cadena_error;
					
					}
					
										//boton volver a alta cliente
					?>
					<p>&nbsp;</p><p>&nbsp;</p>
					<form action="gruposderecarga.php" method="post">
					
					<input type="submit" value="Volver a grupos de recarga" style=" width:120;height:20; background-image:url(./images/bg_button_big.gif)"> 
					</form>					
					</div>
					
					
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
