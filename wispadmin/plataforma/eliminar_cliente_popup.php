<?php
include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];
$name=$_GET['name'];
$idCliente=$_GET['idCliente'];



?>
<html>
<head>

<SCRIPT LANGUAGE="Javascript">

	function confirmacion_total(name,idCliente)
	{

		opener.location=('confirmar_eliminacion_cliente.php?name='+name+'&idCliente='+idCliente); 
		self.close();
		
		//NOS KEDAMOS POR AKI, HABRIA QUE HACER LAS VENTANAS DE CONFIRMACION_dE RECARGA, HACERLA EN LA BD Y RECARGA_CANCELADA
	}

	function cancelacion_total()
	{

		opener.location=('eliminacion_cancelada.php'); 
		self.close();
	}
</SCRIPT>

<title>Confirmar eliminación</title>

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
					


					<div align="center">	
					<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>

					<?php echo "<p>¿Confirma que desea eliminar el cliente <b>$name</b>?</p>";
					   //echo "<p>Tras la operación se repondrá el saldo de la siguiente manera:<br><br><br></p>";
					?>
					<!--<table  border="1" cellpadding="0" cellspacing="0" align="center">
					
					
					<tr>
					<td width="30%">Usuario</td>
					<td width="30%" align="center">Saldo Ahora</td>
					<td width="30%" align="center">Saldo Despues</td>
					</tr>
					<tr>
					<td width="30%"><?php //echo $superusu;?></td>
					<td width="30%" align="center"><?php  //echo redondear_tres_decimal($saldosuperusuario)." €";?></td> 
					<td width="30%" align="center"><?php //echo redondear_tres_decimal($saldosuperusuario+$saldocliente)." €";?></td>
					</tr>
					<tr>
					<td width="30%"><?php //echo $cif_cliente;?></td>
					<td width="30%" align="center"><?php //echo redondear_tres_decimal($saldocliente)." €";?></td> 
					<td width="30%" align="center"><?php //echo redondear_tres_decimal($saldocliente - $saldocliente)." €";?></td>
					</tr>

					</table>-->
					<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>

		
				
					<input type="button" value="Eliminar" onclick="confirmacion_total('<?php echo $name;?>','<?php echo $idCliente;?>')" style=" width:60;height:20; background-image:url(./images/bg_button.gif)"> 
					<input type="button" value="Cancelar" onclick="cancelacion_total()" style=" width:60;height:20; background-image:url(./images/bg_button.gif)"> 

					

					
					</div>

<?php

//confirmar recarga



?>

</body>
</html>


