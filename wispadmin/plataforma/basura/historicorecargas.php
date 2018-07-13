<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];

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
			
					<h4 class="title">Histórico Recargas</h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
					
					<form action="calculohistoricorecargas.php" method="post" name="form1">
					<div align="center">
					
					<p>
					1) Seleccione año :                    
					</p>
					<p>
					&nbsp;
					</p>
					  <label>
					  <select  name="ano">
					    <OPTION VALUE="2011">2011</OPTION>
						<OPTION VALUE="2012">2012</OPTION>
						<OPTION VALUE="2013">2013</OPTION>
						<OPTION VALUE="2014">2014</OPTION>
						<OPTION VALUE="2015">2015</OPTION>
						<OPTION VALUE="2016">2016</OPTION>
						<OPTION VALUE="2017">2017</OPTION>
						<OPTION VALUE="2018">2018</OPTION>
						<OPTION VALUE="2019">2019</OPTION>
						<OPTION VALUE="2020">2020</OPTION>
				      </select>
					  </label>					
					
					<p>
					&nbsp;
					</p>
					<p>
					&nbsp;
					</p>
					<p>
					1) Seleccione mes a mostrar :                    
					</p>
					<p>
					&nbsp;
					</p>
					  <label>
					  <select  name="meses">
						<OPTION VALUE="00">Todos los meses</OPTION>
					    <OPTION VALUE="01">Enero</OPTION>
						<OPTION VALUE="02">Febrero</OPTION>
						<OPTION VALUE="03">Marzo</OPTION>
						<OPTION VALUE="04">Abril</OPTION>
						<OPTION VALUE="05">Mayo</OPTION>
						<OPTION VALUE="06">Junio</OPTION>
						<OPTION VALUE="07">Julio</OPTION>
						<OPTION VALUE="08">Agosto</OPTION>
						<OPTION VALUE="09">Septiembre</OPTION>
						<OPTION VALUE="10">Octubre</OPTION>
						<OPTION VALUE="11">Noviembre</OPTION>
						<OPTION VALUE="12">Diciembre</OPTION> 
				      </select>
					  </label>
					
					</div>
					<p align="center">&nbsp;</p>
					<input type="submit" value="Mostrar Datos" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 

					</form>
					
					
					
		</div>
					
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

	<!-- el footer de todalavida-->
		<div id="footer">
		<?php pie(); ?>
	    </div>
</div>

</body>
</html>