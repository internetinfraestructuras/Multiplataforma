<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$name=$_GET['name'];
//$cif_cliente=$_GET['cliente'];

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
		
    function showMe () {
        
		//alert("hola");
		//alert(box);
       /* var chboxs = document.getElementsByName("c1");
        var vis = "none";
        for(var i=0;i<chboxs.length;i++) { 
            if(chboxs[i].checked){
             vis = "block";
                break;
            }
        }*/
        document.getElementById('div1').style.display = "block";
    
    
    }
	
	//oculto campo para poner ip
    function hideMe () {
        
        document.getElementById('div1').style.display = "none";
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
			
					<p align="center">&nbsp;</p>


					
					<h4 class="title">Password Cliente</h4>	
					<p align="center">&nbsp;</p>

					
					<form action="senal_cliente.php" method="post" name="form_cliente" width="30%">
					<div align="center">
					
					</p>
					<p>

					<table  border="0" cellpadding="0" cellspacing="0" width="50%">
					<caption>Introduzca password antena cliente</caption>
					<tr>
					
					<td width="10%" align="center">Password:&nbsp;&nbsp;<input type="password" align="center" 
					size="50" name="password_cliente" >
					
					<input type="hidden" align="center" 
					size="53" name="name" value="<?php echo $name;?>"></td>
					</tr>
					
					
					</table> 	
					<p>
					&nbsp;
					</p>
					<p>
					
					</p>
					</div>
					<p align="center">&nbsp;</p>
					<div align="center">
					
					<input type="submit" value="Consultar" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	&nbsp;&nbsp;&nbsp;

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
