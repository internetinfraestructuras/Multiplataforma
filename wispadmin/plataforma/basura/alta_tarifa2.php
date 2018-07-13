<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$superuser=$_POST['superuser'];



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
		 @import "style/codebox.css";
	</style>

	<style type="text/css" title="currentStyle" media="screen">
		 @import "style/stylesheet.css";
	</style>
	


	<style type="text/css" title="currentStyle" media="screen">
		 @import "style/req.css";
	</style>
	
<STYLE>

UL     { cursor: hand; }
UL LI                { display: none;font: 18pt;list-style: square; }
UL.showList LI       { display: block; }
.defaultStyles UL    { color: orange; }
UL.defaultStyles LI  { display: none; }

.div_que_centra{
	width: 650px;
	margin: 0 auto;
	text-align: left;
	border: none;
}

</STYLE>
	
	
	<!--link rel="Shortcut Icon" type="image/x-icon" href="images/favicon.ico" /-->	
	<script language="javascript" type="text/javascript" src="datetimepicker.js">
	
	</script>
	<script>
	
	var prefijos_aceptados = new Array();
	
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
	
	
	
	function marcar_grupo(formulario,nombregrupo,status)
	{
		//alert(formulario);
		//alert(nombregrupo);
		var n;
		n=formulario.elements.length;

		 
		for (i=0,i<n;i<n;i++)
		{	
			if (formulario.elements[i].className.indexOf(nombregrupo) !=-1) 
			{
				formulario.elements[i].checked = status;
			}
		}			

		//document.getElementById(superul).className = 'defaultStyles';
		
		var clasedesplegada='showList';
		var claseplegada= 'defaultStyles';
		
		//Si estoy poniendo a false => se va a cerrar el chequeo => busco el desplegado y lo pliego
       if(status==false) 
	   {
			var allHTMLTags = new Array();
			//alert("voy a plegar");

			//Create Array of All HTML Tags
			var allHTMLTags=document.getElementsByTagName("ul");

			//Loop through all tags using a for loop
			for (i=0; i<allHTMLTags.length; i++) {

				//alert(allHTMLTags[i].className);
				//Get all tags with the specified class name.
				//alert(allHTMLTags[i].className);
				if (allHTMLTags[i].className==nombregrupo || allHTMLTags[i].className==clasedesplegada ) {

					//Place any code you want to apply to all
					//pages with the class specified.
					//In this example is to “display:none;” them
					//Making them all dissapear on the page.
					//alert("encontrada clase a plegar");

					//allHTMLTags[i].className = 'defaultStyles';
					//allHTMLTags[i].style.display ="none";
					var liNodes = allHTMLTags[i].getElementsByTagName("li"); 	

					for( var j = 0; j < liNodes.length; j++ )
					{
					
						if (liNodes[j].className.indexOf(nombregrupo) !=-1)
						{		
						  //alert("nodo a plegar: " + j);
					      liNodes[j].style.display ="none";
						  
						  	if(liNodes[j].id!='vacio')
							{
							
								//eliminamos dicho elemento en el vector de prefijos
								//alert("prefijo a borrar" + liNodes[j].id);	
							
								//recorro el vector y borro	
								for (z=0;z< prefijos_aceptados.length ; z++)
								{
									if(prefijos_aceptados[z]==liNodes[j].id)
									{
										//alert("borro el prefijo: "+liNodes[j].id +" del vector");
										prefijos_aceptados.splice(z,1);			  
									}
						  
								}

								//muestro el vector
								//alert("asi queda ahora el contenido del vector");
								//for (z=0;z< prefijos_aceptados.length ; z++)
									//alert("z=" + z + "->> " + prefijos_aceptados[z]);
						    }
						  
						}
					}
				}
			}
		}
		
				//Si estoy poniendo a true => se va a abrir el chequeo => busco el plegado y lo despliego
if(status==true) 
	   {
			var allHTMLTags = new Array();
			//alert("busco plegados");

			//Create Array of All HTML Tags
			var allHTMLTags=document.getElementsByTagName("ul");

			//Loop through all tags using a for loop
			for (i=0; i<allHTMLTags.length; i++) {

				//alert(allHTMLTags[i].className);
				//Get all tags with the specified class name.
				
				
				
				
				if (allHTMLTags[i].className==nombregrupo) {	

					//Place any code you want to apply to all
					//pages with the class specified.
					//In this example is to “display:none;” them
					//Making them all dissapear on the page.
					//alert("existe uno con la clase plegada");

					//allHTMLTags[i].className = 'defaultStyles';
					//allHTMLTags[i].style.display ="none";
					var liNodes = allHTMLTags[i].getElementsByTagName("li"); 	

					for( var j = 0; j < liNodes.length; j++ )
					{
					
						if (liNodes[j].className.indexOf(nombregrupo) !=-1)
						{	
							//alert("subnodo a plegado a desplegar");
							liNodes[j].style.display ="block";
							//alert("prefijo" + liNodes[j].id);	
							//añado ese prefijo a la lista de aceptados
							//uso el id del elemento LI como valor de prefijo
							if(liNodes[j].id!='vacio')
							{
							    prefijos_aceptados.push(liNodes[j].id);
								//muestro el vector
								//alert("contenido vector");
								//for (z=0;z< prefijos_aceptados.length ; z++)
										//alert("z=" + z + "->> " + prefijos_aceptados[z]);
							}
							
					   }
						
					}
				}
			}
		}		

		
		

	}
	
	
	function li_borrar_prefijo_vector(valor,status)
	{
        //si despico => elimino del vector de prefijos
		if(status==false)
		{	
			for (z=0;z< prefijos_aceptados.length ; z++)
			{
				if(prefijos_aceptados[z]==valor)
				{
					//alert("borro el prefijo: "+valor +" del vector");
					prefijos_aceptados.splice(z,1);			  
				}
				  
			}

			//muestro el vector
			//alert("asi queda ahora el contenido del vector");
			//for (z=0;z< prefijos_aceptados.length ; z++)
				//alert("z=" + z + "->> " + prefijos_aceptados[z]);	
	
		}
		else //añado al vector de prefijos => nunca voy a chequear esto estando el prefijo ya en el vector... CORRECTO
		{
			prefijos_aceptados.push(valor);
			//muestro el vector
			//alert("contenido vector");
			//for (z=0;z< prefijos_aceptados.length ; z++)
				//alert("z=" + z + "->> " + prefijos_aceptados[z]);
		
		}
		
		
	}

	function pasarvector()
	{
		var arve = prefijos_aceptados.toString();
		// This line converts js array to String 
		 document.form_cliente.arv.value=arve;
		// This sets the string to the hidden form field. 
	}

		
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
			
			         
					
					<h4 class="title">Alta Tarifa Redistribucion para: <?php echo $superuser;?> (selección)</h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
					</div>
					
					<!-- Muestro las tarifas disponibles en tablaprefijos_prueba-->
					
					<form action="alta_tarifa_redistribucion_confirmacion_lista.php" method="post" name="form_cliente" onSubmit="pasarvector()">
					<input name="arv" type="hidden">
					<input type="hidden" align="center" size="53" name="form_superuser" value="<?php echo $superuser;?>">
					<?php
					$tablaprefijos="tablaprefijos_prueba";
					
					echo "<table width='100%' align='center'>";
					echo "<caption> Prefijos y tarifas disponibles </caption>";
					echo "</table>";	
					//echo "<div align='center'> Un click para desplegar, dos para replegar</div>";
					
					

					$colorfila=0;	
					//conectamos a  MYSQL

					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
						
					//opcion 2, la wena, nombre de grupo y en desplegable los prefijos existentes
					
					$tablaprefijos="tablaprefijos_prueba";
					
					//echo "<table width='100%'>";
					//echo "<caption>Tarifas disponibles</caption><thead><tr><td>Grupo</td><td>Prefijo</td><td>Descripcion</td><td>Coste</td></thead><tbody>";

					$colorfila=0;	
					//conectamos a  MYSQL

					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
						
					$sel = "select grupo from $tablaprefijos group by grupo order by grupo limit 10";	
					//$sel = "select grupo,prefijo,descripcion,coste from $tablaprefijos order by grupo";
					$query = mysql_query($sel) or die(mysql_error());
					
					while ($row = mysql_fetch_assoc($query)) 
					{									
						//$row['grupo']
						$grup=$row['grupo'];
						$grupsinespacios = ereg_replace( "([     ]+)", "", $grup ); 
						echo "<br>";
						echo "<div align='left' class='div_que_centra'>";


				echo "<UL  class='$grupsinespacios'> <input type=\"checkbox\" name='$nbrgrupsinespacios' onClick=\"marcar_grupo(this.form,'$grupsinespacios',this.checked)\">  $grup";
//	echo "<UL  class='$grupsinespacios'> <INPUT TYPE=CHECKBOX NAME='$nbrgrupsinespacios' onClick=\"hecked)\">  $grup";

							
										
						$sel2 = "select grupo,prefijo,descripcion,coste from $tablaprefijos where grupo='$grup'";
						$querye = mysql_query($sel2) or die(mysql_error());
						echo "<li class='$grupsinespacios' id='vacio'>&nbsp;";
						//echo "<li class='$grupsinespacios'>";
						echo "<table width='100%'>";
						echo "<thead><tr><td>Check</td><td>Grupo</td><td>Prefijo</td><td>Descripcion</td><td>Coste</td></thead><tbody>";

							while ($rowe = mysql_fetch_assoc($querye)) 
							{
									$prefix=$rowe['prefijo'];
									
								if($colorfila%2==0)
								{
								 echo "<li class='$grupsinespacios' id='$prefix'  ><tr><td><input TYPE=\"checkbox\" name='$grupsinespacios' class='$grupsinespacios' onClick=\"li_borrar_prefijo_vector('$prefix',this.checked)\" ></td><td>".$rowe['grupo']."</td><td>".$rowe['prefijo']."</td><td>".$rowe['descripcion']."</td><td>".$rowe['coste']."</td></tr>";
								}
								else
								{							
								echo "<li class='$grupsinespacios' id='$prefix' ><tr class=\"odd\"><td><input TYPE=\"checkbox\" ='$grupsinespacios'  class='$grupsinespacios' onClick=\"li_borrar_prefijo_vector('$prefix',this.checked)\"></td><td>".$rowe['grupo']."</td><td>".$rowe['prefijo']."</td><td>".$rowe['descripcion']."</td><td>".$rowe['coste']."</td></tr>";
								}	
								$colorfila++;
							}
							$colorfila=0;
						
						echo "</tbody>";
						echo "</table>";
						echo "</ul>";
						echo "</div>";
						

					}					
								
					
					mysql_close($db);		
						
				
					
					?>
					
					<input type="submit" value="Añadir Tarifa" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	&nbsp;&nbsp;&nbsp;

					</form>
					
					
					<!-- <form action="alta_tarifa_redistribucion_efectivo.php" method="post" name="form_cliente" width="30%">
					<div align="center">
					
					</p>
					<p>

					<table  border="1" cellpadding="0" cellspacing="0">
					<caption>Datos Tarifa</caption>
					<tr>
					<td width="20%">Prefijo: </td>
					<td width="30%" align="center"><input type="text" align="center" size="53" onkeypress="return validar_texto(event)" name="form_prefijo" value=""></td>
					<input type="hidden" align="center" size="53" name="form_superuser" value="<?php echo $superuser;?>">
					
					</tr>
					<tr>
					<td width="20%">Coste(euros/min): </td>
					<td width="30%" align="center"><input type="text" size="53" onkeypress="return validar_texto(event)"name="form_coste" value=""></td>
					</tr>
					<tr>
					<td width="20%">Descripcion: </td>
					<td width="30%" align="center"><input type="text" size="53" name="form_descripcion" value=""></td>
					</tr>
					</table> 					
					
					</div>
					<p align="center">&nbsp;</p>
					<div align="center">
					
					<input type="submit" value="Añadir Tarifa" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	&nbsp;&nbsp;&nbsp;

					</div>
					</form>-->

					


	             <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
	
		</div>
		<div id="left-sidebar">
				<div class="block recent-matches">
					<?php  menu_principal_trucho(); ?>
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