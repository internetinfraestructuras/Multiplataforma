<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$callcenter= $_GET['callcenter'];
$troncal = $_GET['usuario_troncal'];


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
	
	<SCRIPT LANGUAGE="Javascript">

	function confirmar_eliminacion_numero()
	{
		troncal=document.form_numericos.usuario_troncal.value;	
		callcenter=document.form_numericos.form_callcenter.value;		
		posicion=document.form_numericos.form_posicion.value;			
		numero=document.form_numericos.form_numero.value;	
	   
		//centrar la ventana
		var w=250;
		var h=150;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
  
	    open('lista_salida_eliminar_num_popup.php?troncal='+troncal+'&callcenter='+callcenter+'&numero='+numero+'&posicion='+posicion,'PopUp','directories=no, scrollbars=yes, resizable=no, height=150,width=300, width='+w+', height='+h+', top='+top+', left='+left);
	  
	
	   
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

					<h4 class="title"><?php echo "Lista numéricos salida troncal : $troncal (".nombre_centralita($callcenter).")";?></h4>			
			        <div align="center"> 
					<p>&nbsp;</p>	
					
					<?php
					
					//superbloque verificacion legitimidad
					//como el id del cliente viene por GET, si un listo lo cambia puede acceder a los clientes
					//de otra peña asi que verifico que el cliente con ese cif es propietario del tio que cursa la sesion
					
					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
						
						
					$sel = "select superusuario from superusuarios where cif_superuser = (select cif_super
					from usuarios where cif = (select cif_user from centralitas where id_centralita='$callcenter'))";
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
						$web_violacion="tarifas_troncal.php";
						
						//notifico la violacion
						$var = " /usr/sendEmail/sendEmail -f plataforma.prepago@gmail.com -t paco.perez@gruporeq.com -s smtp.gmail.com -u Plataforma REQ: Intento violacion por metodo GET   -v -o tls=yes -xu plataforma.prepago -xp gruporeq1430 -m EL SuperUsuario: $usuario, intento acceder al callcenter del usuario=$cif_usuario que realmente es de $superuser_propietario_legitimo en la seccion $web_violacion desde la ip $ip_acceso";
						exec($var);
						
						//cierro sesiones
						session_start();
						session_destroy();
					}
					else	
					{
					
					
					//$callcenter_nbr= nombre_centralita($callcenter);	
					echo"<div align='center'><p>&nbsp;</p><p> A continuación se listan los números que rotarán en las llamadas salientes así como el umbral
					de rotación de dicha serie, ambos parametros son editables<p></div>";
					echo "<table width='100%'>";
					echo "<caption>Numericos asociados y posición</caption>
					<thead><tr><td>Posición</td><td>Número Salida</td><td>Edición</td></thead><tbody>";

					$colorfila=0;	
  				
					//conectamos a  MYSQL

					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
						
						
					//$sel = "select * from tarifasminimassuperusuario where cif_super ='$cif'";
					$sel = "select * from lista_numeros_callcenter where usuario_troncal= ( select usuario_troncal from troncales where usuario_troncal='$troncal' and id_centralita='$callcenter')";

					$query = mysql_query($sel) or die(mysql_error());
						
					$numero_registros = mysql_num_rows($query)-1; // obtenemos el número de filas 	
					// -1 pk colorfila empieza en 0	
						
					//solo doy capacidad de eliminar el ultimo registro para que no se que me queden huecos
					//en medio y demás
					while ($row = mysql_fetch_assoc($query)) 
					{
														
											
						if($colorfila%2==0)
						{
							
							if($colorfila == $numero_registros)
							{  
							 	 echo "<tr><td>".$row['posicion']."</td><td>".$row['numero']."</td><td align=\"center\"><a title='Editar'  href=\"lista_salida_numericos_edit_num.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."&posicion=".$row['posicion']."\">Editar</a> / <a title='Eliminar'  href=\"javascript:confirmar_eliminacion_numero()\">Eliminar</a></td></tr>";
					        }
							else
							{
							 	 echo "<tr><td>".$row['posicion']."</td><td>".$row['numero']."</td><td align=\"center\"><a title='Editar'  href=\"lista_salida_numericos_edit_num.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."&posicion=".$row['posicion']."\">Editar</a></td></tr>";
					        }
							
						
						}
						else
						{		
							if($colorfila == $numero_registros)
							{
							   echo "<tr class=\"odd\"><td>".$row['posicion']."</td><td>".$row['numero']."</td><td align=\"center\"><a title='Editar'  href=\"lista_salida_numericos_edit_num.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."&posicion=".$row['posicion']."\">Editar</a> / <a title='Eliminar'  href=\"javascript:confirmar_eliminacion_numero()\">Eliminar</a></td></tr>";
							}
							else
							{
							     echo "<tr class=\"odd\"><td>".$row['posicion']."</td><td>".$row['numero']."</td><td align=\"center\"><a title='Editar'  href=\"lista_salida_numericos_edit_num.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."&posicion=".$row['posicion']."\">Editar</a></td></tr>";
							}
						
						}
						$numero_final = $row['numero'];
						$colorfila++;
					}
						
					mysql_close($db);	
						
					echo "</tbody>";
					echo "</table>";
					?>					
					
					<p>&nbsp;</p>		
					<p>&nbsp;</p>		
					<form action="lista_salida_numericos_add.php" method="post" name="form_numericos" width="30%">
					<div align="center">
					
					<input type="hidden" size="53" name="usuario_troncal" value="<?php echo $troncal;?>"></td>
					<input type="hidden" size="53" name="form_callcenter" value="<?php echo $callcenter;?>"></td>
					<!-- solo para ser recogido por la funcion eliminar de javascript, no me sirve para el post -->
					<input type="hidden" size="53" name="form_posicion" value="<?php echo $numero_registros;?>"></td>
					<input type="hidden" size="53" name="form_numero" value="<?php echo $numero_final;?>"></td>
					<!-- fin -->
					
					<input type="submit" value="Añadir numérico" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	
			
					</div>
					</form>
					<p>&nbsp;</p>						
						
					<?php	

					//seccion del umbral de rotacion

					echo"<div align='center'><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>El umbral de rotación define el valor a partir
					del cual se cambia de numerico de salida de forma secuencial<p></div>";
					echo "<table width='100%'>";
					echo "<caption>Umbral de rotación</caption>
					<thead><tr><td>Umbral</td><td>Edición</td></thead><tbody>";
					
					$sel = "select umbral_cambio_cli from troncales where usuario_troncal= ( select usuario_troncal from troncales where usuario_troncal='$troncal' and id_centralita='$callcenter')";

					$query = mysql_query($sel) or die(mysql_error());
						
					while ($row = mysql_fetch_assoc($query)) 
					{
						if( $row['umbral_cambio_cli'] ==0)
						{
						  echo "<tr><td>Umbral</td><td>
						  Error, no establecido, <a title='Editar'  href=\"editar_umbral_cambio_cli.php?callcenter=".$callcenter."&usuario_troncal=".$troncal."\">editar</a> y establecer</td></tr>";
						}
						else
						{
						  echo "<tr><td>Umbral</td><td>
						  <a title='Editar'  href=\"editar_umbral_cambio_cli.php?callcenter=".$callcenter."&usuario_troncal=".$troncal."\">Cada ".$row['umbral_cambio_cli']." llamadas </a></td></tr>";
						}
					}
						
					
					mysql_close($db);		
						
					echo "</tbody>";
					echo "</table>";	
						
						
						
					
					?>							
					
					
															<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
					</div>
					
					<form action="nadanada.php" method="post" name="form_centralita" width="30%">
					<div align="center">
					
					<input type="hidden" size="53" name="usuario_troncal" value="<?php echo $troncal;?>"></td>
					<input type="hidden" size="53" name="form_callcenter" value="<?php echo $callcenter;?>"></td>
					
					<input type="button" value="Volver a Troncales"  onClick="location.href = 'troncales_callcenter.php?callcenter=<?php echo $callcenter; ?>'"  style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 


					</div>
					</form>
					<p>&nbsp;</p>
					
					
					

	             <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
				 
				 <?php } ?>
	
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
