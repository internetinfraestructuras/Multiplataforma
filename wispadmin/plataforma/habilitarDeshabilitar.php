<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$name=$_GET['name'];
$disabled=$_GET['disabled'];

if($disabled=="true")
	$habilitado="no";
else
	$habilitado="si";


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="ClanTemplates" />
	<meta name="keywords" content="Clan" />
	<meta name="description" content="Grupo REQ WISP Admin" />
	<meta name="robots" content="all" />
	<title>WISP Admin Grupo REQ</title>

	<style type="text/css" title="currentStyle" media="screen">
		 @import "style/stylesheet.css";
	</style>

	<style type="text/css" title="currentStyle" media="screen">
		 @import "style/req.css";
	</style>
	
	<script>
	function bloquear(valor)
	{ 
		document.form_cliente.form_habilitado.value=valor;	
		//alert("hola");
	}
	</script>	

</head>

<body>

<div id="container">
	<div id="banner">
	    <?php mostrar_datos_user($_SESSION['nombre_usuario']) ?>
	    <h1 id="logo"><a href="#" title="WISP Admin">WISP Admin</a></h1>
	</div>
	
	<ul id="navigation">

	</ul>
	
	<!-- contenedor principal donde desplegar informacion -->
	<div id="main">
		<div id="content">
			
			         
					<h4 class="title">Editando <?php echo $name;?></h4>	
					<p align="center">&nbsp;</p>
					
					<form action="habilitarDeshabilitar_efectivo.php" method="post" name="form_cliente" width="30%">
					<div align="center">
					
					Bloqueado&nbsp;

					<input type="hidden" name="form_habilitado" value="<?php echo $habilitado;?>">
					<input type="hidden" name="name" value="<?php echo $name;?>">
					
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