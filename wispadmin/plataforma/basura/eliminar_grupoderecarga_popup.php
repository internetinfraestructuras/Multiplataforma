<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$nombregrupo=$_GET['gruporecarga'];
$cif_super_get=$_GET['cif_super'];




?>
<html>
<head>

<SCRIPT LANGUAGE="Javascript">

	function confirmacion_total(masteruser,cif)
	{

		opener.location=('confirmar_eliminacion_grupoderecarga.php?cif_super='+masteruser+'&nombregrupo='+cif); 
		self.close();
		
		//NOS KEDAMOS POR AKI, HABRIA QUE HACER LAS VENTANAS DE CONFIRMACION_dE RECARGA, HACERLA EN LA BD Y RECARGA_CANCELADA
	}

	function cancelacion_total()
	{

		opener.location=('eliminacion_cancelada_grupo.php'); 
		self.close();
	}
</SCRIPT>

<title>Confirmar eliminaci�n</title>

	<style type="text/css" title="currentStyle" media="screen">
		 @import "style/stylesheet_popup.css";
table {

	width: 70%;

	margin:0; 

 	padding:0;

	font-family: "Trebuchet MS", Trebuchet, Arial, sans-serif;	

	color: black;
	

}

table, tr, th, td {

	border-collapse: collapse;

}

caption {

margin:0; 

 	padding:0;


	height: 40px;

	line-height: 40px;

	text-indent: 28px;

	font-family: "Trebuchet MS", Trebuchet, Arial, sans-serif;	

	font-size: 14px;

	font-weight: bold;

	color: black;

	text-align: center;

	letter-spacing: 3px;

	border-top: dashed 1px 	black;

	border-bottom: dashed 1px black;

}



/* HEAD */



thead {

	border: none;
	text-align:center;
	color: black;
	font-size: 10px;
	background: #acaaa7;

}

thead tr th {

	height: 25px;

	line-height: 25px;

	text-align: center;

	color: #1c5d79;

	background: #acaaa7;
	

	background-repeat: repeat-x;

	border-left:solid 1px #FF9900;

	border-right:solid 1px #FF9900;	

	border-collapse: collapse;
    		text-align:center;

	

}



/* BODY */



tbody tr {

	background: #dadcdc;

	font-size: 11px;
	

}

tbody tr.odd {

	background: #f3d4b2;

}

tbody tr.total {

	background: #ff8400;

}

tbody tr:hover, tbody tr.odd:hover {

	background: #ffffff;

}

tbody tr th, tbody tr td {

	padding: 6px;

	border: solid 1px black;

}

tbody tr th {

	background: #1c5d79;

	font-family: "Trebuchet MS", Trebuchet, Arial, sans-serif;	

	font-size: 11px;

	padding: 6px;

	text-align: center;

	font-weight: bold;

	color: #FFFFFF;

	border-bottom: solid 1px white;

}

tbody tr th:hover {

	background: #ffffff;

}		 

</style>

</head>
<body>
					<?php
					
					//superbloque verificacion legitimidad
					//como el id del cliente viene por GET, si un listo lo cambia puede acceder a los clientes
					//de otra pe�a asi que verifico que el cliente con ese cif es propietario del tio que cursa la sesion
					
					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
						
						
				$sel = "select superusuario from superusuarios where cif_superuser = (select cif_super
					from gruposderecarga where cif_super = '$cif_super_get' and nombregrupo='$nombregrupo')";
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
						$servicio = "eliminar_cliente_popup.php";
						
						//notifico la violacion
						$var = " /usr/sendEmail/sendEmail -f plataforma.prepago@gmail.com -t paco.perez@gruporeq.com -s smtp.gmail.com -u Plataforma REQ: Intento violacion por metodo GET   -v -o tls=yes -xu plataforma.prepago -xp gruporeq1430 -m EL SuperUsuario: $usuario, intento acceder al usuario=$cif_usuario que realmente es de $superuser_propietario_legitimo en la seccion $servicio desde la ip $ip_acceso";
						exec($var);
						
						//cierro sesiones
						session_start();
						session_destroy();
					}
					else
					{
					
					//conectamos a  MYSQL

					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
						
						
					/*$sel = "select saldo from superusuarios where superusuario='$superusu'";
					$query = mysql_query($sel) or die(mysql_error());
						
					while ($row = mysql_fetch_assoc($query)) 
					{
						$saldosuperusuario=$row['saldo'];
					}*/
						
					/*$sel = "select saldo from usuarios where cif='$cif_cliente'";
					$query = mysql_query($sel) or die(mysql_error());
						
					while ($row = mysql_fetch_assoc($query)) 
					{
						$saldocliente=$row['saldo'];
					}

						
					mysql_close($db);		
						*/
					?>

					<div align="center">	
					<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>

					<?php echo "<p>�Confirma que desea eliminar el grupo <b>$nombregrupo</b>?<br>&nbsp;<br>
									Los clientes actualmente asociados al grupo <b>$nombregrupo</b> se quedar�n sin ninguno asociado</p>";
					   //echo "<p>Tras la operaci�n se repondr� el saldo de la siguiente manera:<br><br><br></p>";
					?>
					<!--<table  border="1" cellpadding="0" cellspacing="0" align="center">
					
					
					<tr>
					<td width="30%">Usuario</td>
					<td width="30%" align="center">Saldo Ahora</td>
					<td width="30%" align="center">Saldo Despues</td>
					</tr>
					<tr>
					<td width="30%"><?php //echo $superusu;?></td>
					<td width="30%" align="center"><?php  //echo redondear_tres_decimal($saldosuperusuario)." �";?></td> 
					<td width="30%" align="center"><?php //echo redondear_tres_decimal($saldosuperusuario+$saldocliente)." �";?></td>
					</tr>
					<tr>
					<td width="30%"><?php //echo $cif_cliente;?></td>
					<td width="30%" align="center"><?php //echo redondear_tres_decimal($saldocliente)." �";?></td> 
					<td width="30%" align="center"><?php //echo redondear_tres_decimal($saldocliente - $saldocliente)." �";?></td>
					</tr>

					</table>-->
					<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>

					<?php } ?>
				
					<input type="button" value="Eliminar" onclick="confirmacion_total('<?php echo $cif_super_get;?>','<?php echo $nombregrupo;?>')" style=" width:60;height:20; background-image:url(./images/bg_button.gif)"> 
					<input type="button" value="Cancelar" onclick="cancelacion_total()" style=" width:60;height:20; background-image:url(./images/bg_button.gif)"> 

					

					
					</div>

<?php

//confirmar recarga



?>

</body>
</html>


