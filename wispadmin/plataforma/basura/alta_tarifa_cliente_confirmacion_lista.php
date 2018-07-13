<?php

include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
/*$coste=$_POST['form_coste'];
$prefijo=$_POST['form_prefijo'];
$descripcion=$_POST['form_descripcion'];*/
$prefijos = $_POST['arv'];
$superuser=$_POST['form_superuser'];
$cif_superuser=$_POST['form_superuser'];
$porcentajebeneficio=$_POST['form_porcentajebeneficio'];
$troncal=$_POST['form_id_troncal'];
$callcenter=$_POST['form_callcenter'];


//si pagina esta seteada la cojo, sino, es la primera
//pagina hace referencia a un indice dentro del vector por donde voy recorriendo
if(isset($_POST['page']))
{
	$page= $_POST['page'];
}
else
{
    //si no es la primera pagina
	$page=0;
}

//si me viene un porcentaje lo cojo, si no lo pongo a 10%
/*if(isset($_POST['form_porcentajebeneficio']))
{
	$porcentajebeneficio= $_POST['form_porcentajebeneficio'];
}
else
{
    //si no es la primera pagina
	$porcentajebeneficio=10;
}*/



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
	
	<style type="text/css" title="currentStyle" media="screen">
	@import "style/paginacion.css";

	</style>	
	
	<script type="text/javascript" src="modificartabla.js"></script>
	<!--link rel="Shortcut Icon" type="image/x-icon" href="images/favicon.ico" /-->	


	<script>
	
	
	//les paso la pagina donde estoy y el tamaño de la pagina
	//al final no hice posible la edicion individual en este paso
	//por lo que los costes se incrementan segun porcentaje con respecto
	//al precio original siempre, no es acumulativo
	/*
	function write_posibles_cost_mod(pagina_actual,tamano_pagina)
	{
		//var prefijos_aceptados = new Array();
		//var costes_asociados = new Array();

		var javascript_vector_costes = new Array();
		var indice;

		<?php
		for ($i = 0; $i < count($vector_costes); $i++){
		?>
			javascript_vector_costes[<?php echo $i ?>] = "<?php echo $vector_costes[$i]; ?>";
		<?php
		}
		?>		
		
		//recorro la tabla y almaceno costes y prefijos
		for (var i=1;i<document.getElementById('tablatarifas').rows.length;i++) 
		{
			for (var j=1;j<5;j++) 
			{
				//textos = textos + document.getElementById('tablatarifas').rows[i].cells[j].innerHTML + "ppppp";
				
				/*if(j==1)//estamos leyendo un prefijo
				{
					//alert("prefijo: " + document.getElementById('tablatarifas').rows[i].cells[j].innerHTML);
					prefijos_aceptados.push(document.getElementById('tablatarifas').rows[i].cells[j].innerHTML);
					
				}*/
				/*
				if(j==4)//estamos leyendo un coste cliente asociado
				{
					//alert("coste cliente: " + document.getElementById('tablatarifas').rows[i].cells[j].innerHTML);
					//costes_asociados.push(document.getElementById('tablatarifas').rows[i].cells[j].innerHTML);
					indice = (pagina_actual*tamano_pagina) + i;
					alert("indice");
					alert(indice);
					alert("coste");
					alert(document.getElementById('tablatarifas').rows[i].cells[j].innerHTML);
					javascript_vector_costes[indice] = (document.getElementById('tablatarifas').rows[i].cells[j].innerHTML);
					
				}
				
			}
		}	
	
		//convierto los vectores a strings para pasarlos por metodo post
		
		// var arve = prefijos_aceptados.toString();
	    // var arv2 = costes_asociados.toString();
		
		var arv3 = javascript_vector_costes.toString();
		
		// This line converts js array to String 
		// document.form_cliente.arv.value=arve;
		 //document.form_cliente.arvcostes.value=arv2;
		 	document.form_cliente.arvcostes.value=arv3;
			document.form_cliente_refresh.arvcostes.value=arv3;
		// This sets the string to the hidden form field. 
	}	

	*/
	</script>
	
	
	
</head>

<!--<body> -->
<body onload="iniciarTabla()">

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
					<h4 class="title">Alta Tarifa Redistribucion para: <?php echo $superuser;?> (verificación) <?php echo $form_cif;?></h4>	
					<p align="center">&nbsp;</p>
					<div align="center">
					
					<?php
					//si no hemos seleccionado prefijos, no seguimos:
					if(empty($prefijos))
					{
					  echo "<h4>No se ha seleccionado ningun prefijo a asociar</h4><br>";
					  echo "<input type='button' value='Cancelar'  onClick=\"location.href = 'tarifas_troncal.php?callcenter=$callcenter&usuario_troncal=$troncal'\"  style=\" width:90;height:90; background-image:url(./images/bg_button.gif)\">"; 
			  
					}   
					else
					{
					 ?>
					
					<h3> Modificación de coste/min para cliente final</h3>
					<br>  <br>
					
					
					
					
					<?php
					
					//conectamos a  MYSQL
					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
						
					//echo "Prefijos-> ".$prefijos;
					//vienen todos los prefijos en un vector separado por comas
					$vector_prefijos = explode(',',$prefijos);  
					
				
					
					//hago el vector de costes y si beneficio >0 actualizo el vector de costes
						
						$num_rows= count($vector_prefijos);
						
						for($i=0;$i<$num_rows;$i++)
						{					
							$prefix=$vector_prefijos[$i];
							$sel ="select * from tarifasminimassuperusuario_prueba where prefijo='$prefix' and cif_super='$cif_superuser'";	
							$query = mysql_query($sel) or die(mysql_error());


							while ($rowe = mysql_fetch_assoc($query)) 
							{
								  $prefijillo=$rowe['prefijo'];
								  $costecillo=$rowe['coste'];
								  //meto los costes en el vector
								  $vector_costes[$i]=$costecillo;	 
							}
									
						}
					
					
					
					//si viene un porcentaje de beneficio > 0 => actualizo el vector de costes
					if($porcentajebeneficio>0)
					{
						for($i=0;$i<$num_rows;$i++)
						{
							$vector_costes[$i]= $vector_costes[$i] + ( $vector_costes[$i] *  $porcentajebeneficio/100);
						}
					}
					
					
					echo "<table width='100%' name='tablatarifas' id='tablatarifas' class='tablatarifas'>";
					$colorfila=0;
					echo "<thead><tr><td>Grupo</td><td>Prefijo</td><td>Descripcion</td><td>Coste proveedor(€/min)</td><td><b>Coste cliente(€/min)(modificar)</b></td></thead><tbody>";

					//numero total de prefijos en la lista
					$num_rows= count($vector_prefijos);
					
					//registros que quiero mostrar por pagina
					$rows_per_page=50;
					
					
					//CALCULO LA ULTIMA PÁGINA
						$lastpage= ceil($num_rows / $rows_per_page);
					
						//COMPRUEBO QUE EL VALOR DE LA PÁGINA SEA CORRECTO Y SI ES LA ULTIMA PÁGINA
						$page=(int)$page;
 
						if($page > $lastpage)
						{
							$page= $lastpage;
						}
 
					/*	if($page < 1)
						{
							$page=1;
						}*/					
					
					if($page > $num_rows)
					{
						$page= $num_rows - $rows_per_page;
					}
					
					for($i=$page*$rows_per_page;$i<(($page*$rows_per_page) + $rows_per_page);$i++)
					{					
						$prefix=$vector_prefijos[$i];
						$costix=$vector_costes[$i];
						
						//$sel ="select * from tablaprefijos_prueba where prefijo='$prefix'";	
						//echo "-------------------".$cif_superuser."8888888888888";
						//echo ".--->".$prefix;
						
						$sel ="select * from tarifasminimassuperusuario_prueba where prefijo='$prefix' and cif_super='$cif_superuser'";	
						$query = mysql_query($sel) or die(mysql_error());


							while ($rowe = mysql_fetch_assoc($query)) 
							{
								  $prefijillo = $prefix;
								  $costecillo = $costix;
								  //$prefijillo=$rowe['prefijo'];
								  //$costecillo=$rowe['coste'];
								  //aplico los porcentajes de beneficio
								/*  if($porcentajebeneficio>0)
								     $costecillo = $costecillo + ($costecillo * $porcentajebeneficio)/100;
									*/
								if($colorfila%2==0)
								{

								  //onClick=\"open('modificar_tarifa_popup.php?prefijo=$prefijillo&coste=$costecillo','PopUp','directories=no, scrollbars=yes, resizable=no, height=300,width=500')
								 //echo "<tr><td>".$rowe['grupo']."</td><td>".$rowe['prefijo']."</td><td>".$rowe['descripcion']."</td><td>".$rowe['coste']." 
								 //<input style=\" width:120;height:90; background-image:url(./images/bg_button.gif)\" value=\"Modificar\" type=\"button\" onClick=\"open('modificar_tarifa_popup.php?prefijo=$prefijillo&coste=$costecillo','PopUp','directories=no, scrollbars=yes, resizable=no, height=300,width=500')\"></td></tr>";
								echo "<tr><td>".$rowe['grupo']."</td><td>".$prefijillo."</td><td>".$rowe['descripcion']."</td><td>".$rowe['coste']."</td><td style='font-weight:bold'>$costecillo</td></tr>";
	
								
								}
								else
								{							
								echo "<tr class=\"odd\"><td>".$rowe['grupo']."</td><td>".$prefijillo."</td><td>".$rowe['descripcion']."</td><td>".$rowe['coste']."</td><td style='font-weight:bold'>$costecillo</td></tr>";
								}	
								$colorfila++;
							}
							
						
					}
					//incremento page para luego pasarlo por post
					$pagesiguientebloque= $page + $rows_per_page;
					
					echo "</table>";	
					mysql_close($db);		

					
					
					?>
					
					<p> &nbsp;</p>
					<p> &nbsp;</p>	
<!-- trozo de paginacion -->
<?php
    //UNA VEZ Q MUESTRO LOS DATOS TENGO Q MOSTRAR EL BLOQUE DE PAGINACIÓN SIEMPRE Y CUANDO HAYA MÁS DE UNA PÁGINA

	  
    if($num_rows > $rows_per_page)
    {

       $Siguientepage= $page +1;
       $prevpage= $page -1;
     
		
       ?>
	   
	   <div align="center">
	   <!-- lo meto en tabla pk no tengo webos de centrar con div -->
	   <table  width='100%' >
	   <tr valign="center">
	   <td valign="center">
	   
	   <ul id="pagination-clean" class="pagination-clean"><?php
           //SI ES LA PRIMERA PÁGINA DESHABILITO EL BOTON DE Anterior, MUESTRO EL 1 COMO ACTIVO Y MUESTRO EL RESTO DE PÁGINAS
           if ($page == 0)
           {
            ?>
              <li class="Anterior-off">&laquo; Anterior</li>
              <li class="active">0</li>
         <?php
              for($i= $page+1; $i< $lastpage ; $i++)
              {?>
                <li>
				
				<!--<a href="tarifas_troncal.php?usuario_troncal=<?php echo $troncal;?>&callcenter=<?php echo $callcenter;?>&page=<?php echo $i;?>"><?php echo $i;?></a>
       -->

					<form action="alta_tarifa_cliente_confirmacion_lista.php" method="post" name="form_cliente_refresh" >
					<input name="arv" type="hidden" value="<?php echo $prefijos;?>">
					
					
					<input name="form_superuser" type="hidden" value="<?php echo $superuser;?>">
					<input type="hidden" align="center" size="53" name="form_id_troncal" value="<?php echo $troncal;?>">
					<input type="hidden" align="center" size="53" name="form_callcenter" value="<?php echo $callcenter;?>">
					<input type='hidden' name='form_porcentajebeneficio' size='3px' value='<?php echo $porcentajebeneficio;?>'>
					<input type="hidden" align="center" size="53" name="page" value="<?php echo $i;?>">
					
					
					<input type="submit" value="<?php echo $i;?>">
					
					</form>
	  
	  
		</li>
	  <?php }
           
           //Y SI LA ULTIMA PÁGINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON Siguiente O LO DESHABILITO
            if($lastpage >$page )
            {?>     
                <li class="Siguiente">
				
				<!--<a href="tarifas_troncal.php?usuario_troncal=<?php echo $troncal;?>&callcenter=<?php echo $callcenter;?>&page=<?php echo $Siguientepage;?>" >Siguiente &raquo;</a>
				-->
				
					<form action="alta_tarifa_cliente_confirmacion_lista.php" method="post" name="form_cliente_refresh">
					<input name="arv" type="hidden" value="<?php echo $prefijos;?>">

										
					<input name="form_superuser" type="hidden" value="<?php echo $superuser;?>">
					<input type="hidden" align="center" size="53" name="form_id_troncal" value="<?php echo $troncal;?>">
					<input type="hidden" align="center" size="53" name="form_callcenter" value="<?php echo $callcenter;?>">
					<input type="hidden" align="center" size="53" name="page" value="<?php echo $Siguientepage;?>">
					<input type='hidden' name='form_porcentajebeneficio' size='3px' value='<?php echo $porcentajebeneficio;?>'>
					
					<input type="submit" value="<?php echo "Siguiente &raquo;";?>">
					
					</form>
				
				
				</li><?php
                
			
			
			}
            else
            {?>
                <li class="Siguiente-off">Siguiente &raquo;</li>
        <?php
            }
        }
        else
        {
     
            //EN CAMBIO SI NO ESTAMOS EN LA PÁGINA UNO HABILITO EL BOTON DE PREVIUS Y MUESTRO LAS DEMÁS
        ?>
            <li class="Anterior">
			
			
			<!--<a href="tarifas_troncal.php?usuario_troncal=<?php echo $troncal;?>&callcenter=<?php echo $callcenter;?>&page=<?php echo $prevpage;?>">&laquo; Anterior</a>
			-->
			
					<form action="alta_tarifa_cliente_confirmacion_lista.php" method="post" name="form_cliente_refresh">
					<input name="arv" type="hidden" value="<?php echo $prefijos;?>">

					
					<input name="form_superuser" type="hidden" value="<?php echo $superuser;?>">
					<input type="hidden" align="center" size="53" name="form_id_troncal" value="<?php echo $troncal;?>">
					<input type="hidden" align="center" size="53" name="form_callcenter" value="<?php echo $callcenter;?>">
					<input type="hidden" align="center" size="53" name="page" value="<?php echo $prevpage;?>">
					<input type='hidden' name='form_porcentajebeneficio' size='3px' value='<?php echo $porcentajebeneficio;?>'>
					
					<input type="submit" value="<?php echo "&laquo; Anterior";?>">
					
					</form>
			</li>
			<?php
            
			for($i= 0; $i< $lastpage ; $i++)
             {
                //COMPRUEBO SI ES LA PÁGINA ACTIVA O NO
                if($page == $i)
                {
            ?>       <li class="active"><?php echo $i;?></li><?php
                }
                else
                {
            ?>       <li>
			
					<!--<a href="tarifas_troncal.php?usuario_troncal=<?php echo $troncal;?>&callcenter=<?php echo $callcenter;?>&page=<?php echo $i;?>" ><?php echo $i;?></a>
					-->	
		
					<form action="alta_tarifa_cliente_confirmacion_lista.php" method="post" name="form_cliente_refresh">
					<input name="arv" type="hidden" value="<?php echo $prefijos;?>">

					
					<input name="form_superuser" type="hidden" value="<?php echo $superuser;?>">
					<input type="hidden" align="center" size="53" name="form_id_troncal" value="<?php echo $troncal;?>">
					<input type="hidden" align="center" size="53" name="form_callcenter" value="<?php echo $callcenter;?>">
					<input type="hidden" align="center" size="53" name="page" value="<?php echo $i;?>">
					<input type='hidden' name='form_porcentajebeneficio' size='3px' value='<?php echo $porcentajebeneficio;?>'>
					
					<input type="submit" value="<?php echo $i;?>">
					
					</form>
		
					
					</li>
			
			
			<?php
                }
            }
             //Y SI NO ES LA ÚLTIMA PÁGINA ACTIVO EL BOTON Siguiente    
            if($lastpage >$page+1 )
            {   ?>  
                <li class="Siguiente">
				
				<!--<a href="tarifas_troncal.php?usuario_troncal=<?php echo $troncal;?>&callcenter=<?php echo $callcenter;?>&page=<?php echo $Siguientepage;?>">Siguiente &raquo;</a>
				-->

					<form action="alta_tarifa_cliente_confirmacion_lista.php" method="post" name="form_cliente_refresh">
					<input name="arv" type="hidden" value="<?php echo $prefijos;?>">
		
					
					<input name="form_superuser" type="hidden" value="<?php echo $superuser;?>">
					<input type="hidden" align="center" size="53" name="form_id_troncal" value="<?php echo $troncal;?>">
					<input type="hidden" align="center" size="53" name="form_callcenter" value="<?php echo $callcenter;?>">
					<input type="hidden" align="center" size="53" name="page" value="<?php echo $Siguientepage;?>">
					<input type='hidden' name='form_porcentajebeneficio' size='3px' value='<?php echo $porcentajebeneficio;?>'>
					
					<input type="submit" value="<?php echo "Siguiente &raquo;";?>">
					
					</form>				

	
				
				
				</li><?php
            }
            else
            {
        ?>       <li class="Siguiente-off">Siguiente &raquo;</li><?php
            }
        }    
    ?>
	</ul>
	
	</td>
	</tr>
	</table>
	</div>
	<?php
    }

 

 
?>
<p> &nbsp;</p>
<p> &nbsp;</p>
<p> &nbsp;</p>



					<!-- fin paginacion -->								
					
					
					
					
					
					
					
					
					<form action="alta_tarifa_cliente_confirmacion_lista.php" method="post" name="form_cliente_refresh">
					<input name="arv" type="hidden" value="<?php echo $prefijos;?>">
					<!--<input name="arvcostes" type="hidden" value="<?php //echo $vector_costes;?>"> -->
					
					<input name="form_superuser" type="hidden" value="<?php echo $superuser;?>">
					<input type="hidden" align="center" size="53" name="form_id_troncal" value="<?php echo $troncal;?>">
					<input type="hidden" align="center" size="53" name="form_callcenter" value="<?php echo $callcenter;?>">
					<br>&nbsp;<br>
					<h4>1.- Aplicar el siguiente porcentaje de beneficio al cliente final sobre los costes de proveedor</h4><br>
					<h4> (Podrá editar costes individuales una vez asignados)</h4><br>
					<br><br>
					Incremento del coste final en un <input type='text' name='form_porcentajebeneficio' size='3px' value='<?php echo $porcentajebeneficio;?>'>%
					<br>&nbsp;<br>
					<input type="submit" value="Aplicar porcentaje y refrescar" style=" width:120;height:90; background-image:url(./images/bg_button.gif)">
					
					</form>
					
					<br><br><br>
					
					<form action="alta_tarifa_cliente_efectivo_lista.php" method="post" name="form_cliente"  >
					<input type="button" value="Cancelar"  onClick="location.href = 'tarifas_troncal.php?callcenter=<?php echo $callcenter; ?>&usuario_troncal=<?php echo $troncal; ?>'"  style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 
			
					<?php 
					
					echo '<input name="arvcostes" type="hidden" value="'.htmlspecialchars( serialize($vector_costes) ).'" />';
						echo '<input name="arv" type="hidden" value="'.htmlspecialchars( serialize($vector_prefijos) ).'" />';
						?>
					
					<input type="hidden" align="center" size="53" name="form_superuser" value="<?php echo $superuser;?>">
					<input type="hidden" align="center" size="53" name="form_id_troncal" value="<?php echo $troncal;?>">
					<input type="hidden" align="center" size="53" name="form_callcenter" value="<?php echo $callcenter;?>">
					
					<input type="submit" value="Aceptar y Asociar Tarifas" style=" width:120;height:90; background-image:url(./images/bg_button.gif)">
					<!--<input type="button" value="pocoyo" onclick="datostabla()">-->
					</form>

					<?php
					}
					?>
					
					</div>
					

					<p align="center">&nbsp;</p>
					
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
