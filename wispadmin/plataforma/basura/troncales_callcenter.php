<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$callcenter=$_GET['callcenter'];

$callcenter_nbr= nombre_centralita($callcenter);


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
			
					<h4 class="title"><?php echo "Troncales para CallCenter : $callcenter_nbr";?></h4>	
			        <div align="center"> 
					<?php
					
					//superbloque verificacion legitimidad
					//como el id del callcenter viene por GET, si un listo lo cambia puede acceder a los callcenters
					//de otra peña asi que verifico que la centralita con ese id es propietaria del tio que cursa la sesion
					
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

					//solo en troncales_callcenter.php
					$sel = "select tipofacturacion from usuarios where cif 
					= (select cif_user from centralitas where id_centralita='$callcenter')";
					$query = mysql_query($sel) or die(mysql_error());
						
					while ($row = mysql_fetch_assoc($query)) 
					{									
						$tipofacturacion=$row['tipofacturacion'];	
						$subtipo = substr($tipofacturacion, 0,4);
					} 							
					
					
					mysql_close($db);						
					
					if($usuario!=$superuser_propietario_legitimo)
					{
						//si son distintos => me quieren hacer la gruya!!!
						echo "<p>&nbsp;</p>Error<p>&nbsp;</p>";
						
						//obtengo la ip de acceso desde donde entran
					    $ip_acceso = obtener_direccion_ip();
						
						//notifico la violacion
						$var = " /usr/sendEmail/sendEmail -f plataforma.prepago@gmail.com -t paco.perez@gruporeq.com -s smtp.gmail.com -u Plataforma REQ: Intento violacion por metodo GET   -v -o tls=yes -xu plataforma.prepago -xp gruporeq1430 -m EL SuperUsuario: $usuario, intento acceder al callcenter=$callcenter que realmente es de $superuser_propietario_legitimo en la seccion troncales_callcenter.php desde la ip $ip_acceso";
						exec($var);
						
						//cierro sesiones
						session_start();
						session_destroy();
					}
					else
					{
					
					echo "<table width='100%'>";
				//	echo "<caption>Troncales</caption><thead><tr><td>Protocolo</td><td>Usuario</td><td>Password</td><td>Caller ID</td><td>Codecs</td><td>Servidor dst.</td><td>Tarifas</td><td>Numericos Entrada</td><td>Bloq./Desbloq.</td><td>Llamadas</td></thead><tbody>";
					echo "<caption>Troncales</caption><thead><tr><td>Protocolo</td><td>Usuario</td><td>Password</td><td>Caller ID</td><td>Servidor dst.</td><td>Tarifas</td><td>Numericos Entrada</td><td>Bloq./Desbloq.</td><td>Llamadas</td></thead><tbody>";

					$colorfila=0;	
					//conectamos a  MYSQL

					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
						
						
					$sel = "select * from troncales where id_centralita='$callcenter'";
					$query = mysql_query($sel) or die(mysql_error());
						
					while ($row = mysql_fetch_assoc($query)) 
					{
						//echo "subtipoooo:".$subtipo;
						/*if($subtipo=="MPRE")
						{
							//si es minutos prepago deshabilito el enlace a tarifas troncal
							$enlace_tarifas="--";
						}
						else
						{*/
						   //si es SPRE => pongo el enlace a las tarifas
						   $enlace_tarifas = "<a title='Editar'  href=\"tarifas_troncal.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."\">Tarifas</a>";
						   
						//}   
						if($row['habilitado']=='no')
						{ 
						  $colordeshabilitado= "class=\"deshabilitado\"";
						  $mostrarleyenda=1;
						}
						else
						{
						   $habi="";
						   $colordeshabilitado="";

						}											
											
											
						if($colorfila%2==0)
						{
							if($row['caller_id']!="lista")
							{
							  echo "<tr $colordeshabilitado><td>".$row['protocolo']."</td><td>".$row['usuario_troncal']."</td><td>".$row['password_troncal']."</td><td>".$row['caller_id']."</td><td>".$row['servidor_destino']."</td><td align=\"center\">".$enlace_tarifas."</td><td align=\"center\"><a title='Numericos'  href=\"numericos_troncal.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."\">Numéricos</a></td><td align=\"center\"><a title='bloq'  href=\"bloqdesblo_troncal.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."\">Bloq/Desbloq</a></td><td align=\"center\"><a title='detalle'  href=\"detalle_llamadas_sel.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."\">Llamadas</a></td></tr>";
							}
							else 
							{
							  echo "<tr $colordeshabilitado><td>".$row['protocolo']."</td><td>".$row['usuario_troncal']."</td><td>".$row['password_troncal']."</td><td align=\"center\"><a title='lista'  href=\"lista_salida_numericos_troncal.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."\">lista</a></td><td>".$row['servidor_destino']."</td><td align=\"center\">".$enlace_tarifas."</td><td align=\"center\"><a title='Numericos'  href=\"numericos_troncal.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."\">Numéricos</a></td><td align=\"center\"><a title='bloq'  href=\"bloqdesblo_troncal.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."\">Bloq/Desbloq</a></td><td align=\"center\"><a title='detalle'  href=\"detalle_llamadas_sel.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."\">Llamadas</a></td></tr>";
							}	
						
						}
						else
						{	
						
							if($row['caller_id']!="lista")
							{
							  echo "<tr $colordeshabilitado><td>".$row['protocolo']."</td><td>".$row['usuario_troncal']."</td><td>".$row['password_troncal']."</td><td>".$row['caller_id']."</td><td>".$row['servidor_destino']."</td><td align=\"center\">".$enlace_tarifas."</td><td align=\"center\"><a title='Numericos'  href=\"numericos_troncal.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."\">Numéricos</a></td><td align=\"center\"><a title='bloq'  href=\"bloqdesblo_troncal.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."\">Bloq/Desbloq</a></td><td align=\"center\"><a title='detalle'  href=\"detalle_llamadas_sel.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."\">Llamadas</a></td></tr>";
							}
							else 
							{
							  echo "<tr $colordeshabilitado><td>".$row['protocolo']."</td><td>".$row['usuario_troncal']."</td><td>".$row['password_troncal']."</td><td align=\"center\"><a title='Lista'  href=\"lista_salida_numericos_troncal.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."\">lista</a></td><td>".$row['servidor_destino']."</td><td align=\"center\">".$enlace_tarifas."</td><td align=\"center\"><a title='Numericos'  href=\"numericos_troncal.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."\">Numéricos</a></td><td align=\"center\"><a title='bloq'  href=\"bloqdesblo_troncal.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."\">Bloq/Desbloq</a></td><td align=\"center\"><a title='detalle'  href=\"detalle_llamadas_sel.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."\">Llamadas</a></td></tr>";
							}	
						}
						$colorfila++;
						
						/* con codecs
						if($colorfila%2==0)
						{
							echo "<tr $colordeshabilitado><td>".$row['protocolo']."</td><td>".$row['usuario_troncal']."</td><td>".$row['password_troncal']."</td><td>".$row['caller_id']."</td><td>".$row['codecs']."</td><td>".$row['servidor_destino']."</td><td align=\"center\"><a title='Editar'  href=\"tarifas_troncal.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."\">Tarifas</a></td><td align=\"center\"><a title='Numericos'  href=\"numericos_troncal.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."\">Numéricos</a></td><td align=\"center\"><a title='bloq'  href=\"bloqdesblo_troncal.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."\">Bloq/Desbloq</a></td><td align=\"center\"><a title='detalle'  href=\"detalle_llamadas_sel.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."\">Llamadas</a></td></tr>";
					    }
						else
						{	
							
							echo "<tr $colordeshabilitado><td>".$row['protocolo']."</td><td>".$row['usuario_troncal']."</td><td>".$row['password_troncal']."</td><td>".$row['caller_id']."</td><td>".$row['codecs']."</td><td>".$row['servidor_destino']."</td><td align=\"center\"><a title='Editar'  href=\"tarifas_troncal.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."\">Tarifas</a></td><td align=\"center\"><a title='Numericos'  href=\"numericos_troncal.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."\">Numéricos</a></td><td align=\"center\"><a title='bloq'  href=\"bloqdesblo_troncal.php?callcenter=".$callcenter."&usuario_troncal=".$row['usuario_troncal']."\">Bloq/Desbloq</a></td></tr>";
					    }
						$colorfila++;
						*/
					}
						
					$sel = "select cif_user from centralitas where id_centralita = '$callcenter'";
					$query = mysql_query($sel) or die(mysql_error());
					
					while ($row = mysql_fetch_assoc($query)) 
					{
						$cif_cliente=$row['cif_user'];
					}					
					
					

						
					echo "</tbody>";
					echo "</table>";
										   
					if( $mostrarleyenda==1)
					{
					
					   echo "	<p>&nbsp;</p>	
					   
					   <div align='right'>
					
					   <img src='rojo.jpg'>&nbsp;</img>&nbsp;&nbsp;Troncal Bloqueada&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							
					   </div>";	
					   		   
					}					
						
					//si el callcenter posee troncales en tramite => no puede solicitar una nueva
					
					
					$sel = "select * from centralitas where id_centralita='$callcenter'";
					$query = mysql_query($sel) or die(mysql_error());
					
					
					$ret = mysql_fetch_array($query); 
								
					$habilitado=$ret['habilitado'];

					if($habilitado=='ptron')
					{
                            //$disable="onclick='no_add_troncal($cif_cliente)'";	
							$disable="onClick=\"alert('Opción temporalmente deshabilitada; existen troncales pendientes para ".$cif_cliente."')\"";
							$disablebutton="button";
					}
					else
					{
							$disable="";
							$disablebutton="submit";
					}
											
					mysql_close($db);	
						
					
					?>		
					<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
					
					<form method="post" name="form_cliente" action="solicitar_troncal.php">
					<input type="hidden" name="callcenter" value="<?php echo $callcenter;?>">
					<input type="hidden" name="cif" value="<?php echo $cif_cliente;?>">
					<input type="button" value="Volver a Callcenters"  onClick="location.href = 'callcenters.php?cliente=<?php echo $cif_cliente; ?>'"  style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 
					<input type="<?php echo $disablebutton;?>" value="Solicitar nueva troncal" <?php echo $disable; ?> style=" width:90;height:90; background-image:url(./images/bg_button.gif)">
					</form>
					<?php } ?>
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