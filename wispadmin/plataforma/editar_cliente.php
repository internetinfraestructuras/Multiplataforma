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
$disabled=$_GET['disabled'];

$fullname=$name;
//echo "-------->".$comment;
//kito la * del idCliente
$idCliente= substr($idCliente, 1);

//kito los prefijos de nombres y comentarios
$form_cif_original=$name;
$name=substr($name, 3);
//por las comillas kito 4
$comment= substr($comment,4);

//echo "<br>-------->".$comment;



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
	<title>WISP Admin</title>

	<style type="text/css" title="currentStyle" media="screen">
		 @import "style/stylesheet.css";
	</style>

	<style type="text/css" title="currentStyle" media="screen">
		 @import "style/req.css";
	</style>
	
	<SCRIPT LANGUAGE="Javascript">
	function confirmar_eliminacion(idCliente)
	{
		name=document.form_cliente.form_cif.value;		
		//alert(name);
		//centrar la ventana
		var w=250;
		var h=150;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
 
		
	   open('eliminar_cliente_popup.php?name='+name+'&idCliente='+idCliente,'PopUp','directories=no, scrollbars=yes, resizable=no, height=150,width=250, width='+w+', height='+h+', top='+top+', left='+left);
	  
	   
	}
	
	function bloquear(valor)
	{ 
		document.form_cliente.form_habilitado.value=valor;	
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
			
			         
					<h4 class="title">Editando <?php echo $name;?></h4>	
					<p align="center">&nbsp;</p>
					
					<form action="editar_cliente_efectivo.php" method="post" name="form_cliente" width="30%">
					<div align="center">
					
					<table  border="1" cellpadding="0" cellspacing="0">
					<caption>Editar Cliente</caption>
					<tr>
					<td width="20%">Cif: </td>
					<td width="30%" align="center"><input type="text" align="center" 
					size="53" name="form_cif" onkeypress="return validar_dni(event)" value=<?php echo $name;?>>
					<input type="hidden" name="form_cif_original" value=<?php echo $form_cif_original;?>>					
					</td>
					</tr>
					<tr>
					<td width="20%">Nombre: </td>
					<td width="30%" align="center"><input type="text" size="53" name="form_nombre" value='<?php echo $comment;?>'></td>
					</tr>
					<tr>
					<td width="20%">Password: </td>
					<td width="30%" align="center"><input type="text" size="53" name="form_password" value=<?php echo $password;?>></td>
					</tr>					
					<tr>
					<td width="20%">Profile: </td>
					<td width="30%" align="center">

						<?php
						if($usuario=='epsilonpc')
						{
					?>
					
                                        <select  name="form_profile" selected="1M">
                                        <option value="5M512">5M/512k</option>
                                        <option value="6M1">6M/1M</option>
                                        <option value="10M2">10M/2M</option>
                                        <option value="20M20">20M/20M</option>
                                        </select>
					
						<?php }
						
						else if ($usuario=='external')
						{
						?>

                                        <select  name="form_profile" selected="1M">
                                        <option value="1M">1 MEGA</option>
                                        <option value="3M">3 MEGAS</option>
										<option value="3M512k">3M/512K</option>
										<option value="5M">5 MEGAS</option>
                                        <option value="6m">6 MEGAS</option>
										<option value="8M">8 MEGAS</option>
                                        <option value="10M">10 MEGAS</option>
                                        <option value="10M2">10/2 MEGAS</option>
                                        <option value="10msim">10/10 MEGAS</option>
										<option value="12M">12 MEGAS</option>
										<option value="20M2">20M/2M</option>
                                        </select>						
						
						<?php }else{ ?>
						
                                        <select  name="form_profile" selected="1M">
                                        <option value="1M">1 MEGA</option>
                                        <option value="3M">3 MEGAS</option>
										<option value="5M">5 MEGAS</option>
                                        <option value="6m">6 MEGAS</option>
										<option value="8M">8 MEGAS</option>
                                        <option value="10M">10 MEGAS</option>
                                        <option value="10M2">10/2 MEGAS</option>
                                        <option value="10msim">10/10 MEGAS</option>
					<option value="12M">12 MEGAS</option>
				<option value="15M5">15/5 MEGAS</option>
                                
        </select>
						
						<?php } ?>
					
					
					
					
					
					</td>
					</tr>	
					<tr>
					<td width="20%">Service: </td>
					<td width="30%" align="center">
					<select  name="form_service" >
					<option value='any' <?php if($service=='any') echo "selected='selected'"?>>ANY</option>
					<option value='pppoe' <?php if($service=='pppoe') echo "selected='selected'"?>>PPPOE</option>					
					</select>
					</td>
					</tr>					
					<tr>
					<td width="20%">Direccion IP: </td>
					<td width="30%" align="center">
					<?php 
						if($ip=="-DHCP-")
						{	
						?>
							<input type="radio" name="form_opcionip" checked="checked" value="dhcp"  onclick="hideMe()">&nbsp;&nbsp;DHCP<br>
							<input type="radio" name="form_opcionip" value="estatica"  onclick="showMe()">&nbsp;&nbsp;Direccion Est�tica
							<div id="div1" style="display:none">
								<br>Especique direccion ip: 
								<input type="text" size="20" name="form_direccionip" >
							</div>
						<?php
						}
						else
						{
						?>	
							<input type="radio" name="form_opcionip"  value="dhcp"  onclick="hideMe()">&nbsp;&nbsp;DHCP<br>
							<input type="radio" name="form_opcionip" checked="checked" value="estatica"  onclick="showMe()">&nbsp;&nbsp;Direccion Est�tica
							<div id="div1" style="display:block">
								<br>Especique direccion ip: 
								<input type="text" size="20" name="form_direccionip" value=<?php echo $ip;?>>
							</div>							
							
							
						<?php }?>	
						
					</td>	
					</td>
					</tr>	
					
					</table> 	
			
					
					
					</div>
					<p align="center">&nbsp;</p>
					<div align="center">
					<input type="button" value="Volver a Clientes"  onClick="location.href = 'clientes.php'"  style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	&nbsp;&nbsp;&nbsp;				
					<input type="submit" value="Aplicar Cambios" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	&nbsp;&nbsp;&nbsp;
					<input type="button" value="Habilitar/Deshabilitar"
					onClick="location.href = 'habilitarDeshabilitar.php?name=<?php echo $fullname;?>&disabled=<?php echo $disabled;?>'"  style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> &nbsp;&nbsp;&nbsp;				
				
					<input type="button" onclick="confirmar_eliminacion('<?php echo $idCliente;?>')" value="Eliminar Cliente" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	

					
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