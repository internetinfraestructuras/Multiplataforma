<?php
include("funciones.php");
include ("configuracion.php");

//tr -s " " ";" < registrosip.txt > a.yea

$fecha = date("Y-m-d H:i:s");
						
//conectamos a  MYSQL
$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
// Seleccionamos la Base de datos
mysql_select_db($nombre_bd) or die(mysql_error());


$vlineas = file("registrosip.txt");
      
//leo linea a linea
foreach ($vlineas as $sLinea)
{
     
	echo $sLinea."<br>"; 
	$separar = explode(';',$sLinea);

	echo "-----------<br>";
	//separo el nombre sip con la /
	$nombre_sip = explode('/',$separar[0]);
   
	//mejoramos la salida
	if($separar[1]=="(Unspecified)")
	{
		$separar[1]="0.0.0.0";
	}
	
	if($separar[6]=="UNKNOWN")
	{
		$separar[6]="DOWN";
	}

	if($separar[6]=="OK")
	{
		$separar[6]="UP";
	}
	
	//echo $nombre_sip[0]."------>".$separar[1]."------>".$separar[6]."<br>";
	//echo "-----------<br>";
	
	//ahi estarian los tres valores ok, ahora toca insertar
	//para evitar siempre intentar meter las troncales de sherry u otra basura que sale
	//en el archivo, meto este filtro
	
	if($separar[6]=="UP" || $separar[6]=="DOWN")
	{
		echo "-----------<br>";
		echo $nombre_sip[0]."------>".$separar[1]."------>".$separar[6]."------>".$fecha."<br>";
		echo "-----------<br>";
		
		$troncal = $nombre_sip[0];
		$ip = $separar[1];
		$estado = $separar[6];
					
		//preparamos la insercion e insertamos el registro
		$sel = "update troncales set estado='$estado' , iporigen='$ip' , fechaactualizacion='$fecha' where usuario_troncal='$troncal'" ;
				
		$query = mysql_query($sel) or die(mysql_error());			
			
			
	}
   
   
}

//cerramos la conexion a la BD
mysql_close($db);	

$var = "rm -rf registrosip.txt";
exec($var);

   
   

?>