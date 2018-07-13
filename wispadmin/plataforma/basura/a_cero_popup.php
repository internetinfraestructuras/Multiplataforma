<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$cif_cliente=$_GET['cif'];
$superusu=$_GET['super'];

?>
<html>
<head>

<SCRIPT LANGUAGE="Javascript">

	function confirmacion_total(cifsuper,cif,importe)
	{

		opener.location=('confirmacion_a_cero.php?cifsuper='+cifsuper+'&cif='+cif); 
		self.close();
		
		//NOS KEDAMOS POR AKI, HABRIA QUE HACER LAS VENTANAS DE CONFIRMACION_dE RECARGA, HACERLA EN LA BD Y RECARGA_CANCELADA
	}

	function cancelacion_total()
	{

		opener.location=('recarga_cancelada.php'); 
		self.close();
	}
	
</SCRIPT>

<title>Confirmar recarga</title>

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
						
					
					//elimino la seccion de quitar saldo al super user, es todo virtual
					//para el cliente, asi, no le resto las recargas al revendedor, sino el saldo
					//solo miro el saldo antes y despues del cliente final
					
					
					$cif = cif_superusuario($superusu);	
					
//------------------------------------------------------------------------------------------------------------------					
					//superbloque verificacion legitimidad
					//como el id del cliente viene por GET, si un listo lo cambia puede acceder a los clientes
					//de otra peña asi que verifico que el cliente con ese cif es propietario del tio que cursa la sesion
					
					$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
						
						
					$sel = "select superusuario from superusuarios where cif_superuser = (select cif_super
					from usuarios where cif = '$cif_cliente')";
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
						$seccion= "recarga_popup.php";
						
						//notifico la violacion
						$var = " /usr/sendEmail/sendEmail -f plataforma.prepago@gmail.com -t paco.perez@gruporeq.com -s smtp.gmail.com -u Plataforma REQ: Intento violacion por metodo GET   -v -o tls=yes -xu plataforma.prepago -xp gruporeq1430 -m EL SuperUsuario: $usuario, intento acceder al callcenter del usuario=$cif_usuario que realmente es de $superuser_propietario_legitimo en la seccion $seccion desde la ip $ip_acceso";
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
						
					/*$sel = "select saldo from superusuarios where cif_superuser='$cif'";
					$query = mysql_query($sel) or die(mysql_error());
						
					while ($row = mysql_fetch_assoc($query)) 
					{
						$saldosuperusuario=$row['saldo'];
					}*/
						
					$sel = "select saldo,tipofacturacion from usuarios where cif='$cif_cliente' and cif_super='$cif'";
					$query = mysql_query($sel) or die(mysql_error());
						
					while ($row = mysql_fetch_assoc($query)) 
					{
						$saldocliente=$row['saldo'];
						$tipofacturacion=$row['tipofacturacion'];						
					}
					
					$subtipo = substr($tipofacturacion, 0,4);	
					if($subtipo=="MPRE")
					{
					  $frasesaldo="Minutos Antes";
					  $frasesaldo2="Minutos Despues";
					  $especie=" mins.";
					}
					else if ($subtipo=="SPRE")
					{
					  $frasesaldo="Saldo Antes(€)";
					  $frasesaldo2="Saldo Despues(€)";
					  $especie=" €";
					}
						
					mysql_close($db);	
									
						
					?>

					<div align="center">	
					<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>

					¿Confirma que desea establecer a 0 los recursos del cliente: <?php echo $cif_cliente ?>
					<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>

				
					<input type="button" value="Confirmar" <?php echo $deshabi; ?> onclick="confirmacion_total('<?php echo $cif;?>','<?php echo $cif_cliente;?>')" style=" width:60;height:20; background-image:url(./images/bg_button.gif)"> 
					<input type="button" value="Cancelar" onclick="cancelacion_total()" style=" width:60;height:20; background-image:url(./images/bg_button.gif)"> 

					<?php } ?>

					
					</div>

<?php

//confirmar recarga



?>

</body>
</html>


