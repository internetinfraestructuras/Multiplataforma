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
	
	<style type="text/css" title="currentStyle" media="screen">
	@import "style/paginacion.css";

	</style>
	
	
	<SCRIPT LANGUAGE="Javascript">
	function confirmar_eliminacion_todas_tarifas()
	{
		troncal=document.form_centralita.usuario_troncal.value;				
	   
		//centrar la ventana
		var w=250;
		var h=150;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
  
	    open('eliminar_todas_tarifas.php?troncal='+troncal,'PopUp','directories=no, scrollbars=yes, resizable=no, height=150,width=250, width='+w+', height='+h+', top='+top+', left='+left);
	   
	   
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

					<h4 class="title"><?php echo "Tarifas para Troncal : $troncal (".nombre_centralita($callcenter).")";?></h4>			
			        <div align="center"> 
	
					
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
					
					$colorfila=0;	
					
					//conectamos a  MYSQL
					$conn=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
					// Seleccionamos la Base de datos
					mysql_select_db($nombre_bd) or die(mysql_error());
		
					//AL PRINCIPIO COMPRUEBO SI HICIERON CLICK EN ALGUNA PÁGINA
					if(isset($_GET['page']))
					{
						$page= $_GET['page'];
					}
					else
					{
						//SI NO DIGO Q ES LA PRIMERA PÁGINA
						$page=1;
					}
						
					$consulta="select * from tarifas_prueba where usuario_troncal= ( select usuario_troncal from troncales where usuario_troncal='$troncal' and id_centralita='$callcenter') order by grupo";
					$datos=mysql_query($consulta,$conn);

					//MIRO CUANTOS DATOS FUERON DEVUELTOS
					$num_rows=mysql_num_rows($datos);
  
					//ACA SE DECIDE CUANTOS RESULTADOS MOSTRAR POR PÁGINA , EN EL EJEMPLO PONGO 15
					$rows_per_page= 30;
  
					//CALCULO LA ULTIMA PÁGINA
					$lastpage= ceil($num_rows / $rows_per_page);
					
					//COMPRUEBO QUE EL VALOR DE LA PÁGINA SEA CORRECTO Y SI ES LA ULTIMA PÁGINA
					$page=(int)$page;
 
					if($page > $lastpage)
					{
						$page= $lastpage;
					}
 
					if($page < 1)
					{
						$page=1;
					}

					//CREO LA SENTENCIA LIMIT PARA AÑADIR A LA CONSULTA QUE DEFINITIVA
					$limit= 'LIMIT '. ($page -1) * $rows_per_page . ',' .$rows_per_page;
  
					//REALIZO LA CONSULTA QUE VA A MOSTRAR LOS DATOS (ES LA ANTERIO + EL $limit)
					$consulta .=" $limit";
					$registros=mysql_query($consulta,$conn);

					if(!$registros)
					{
						//SI FALLA LA CONSULTA MUESTRO ERROR
						die('Invalid query: ' . mysql_error());
					}
					else
					{
							//SI ES CORRECTA MUESTRO LOS DATOS
					?> 
	  
					<table width='100%'>
					<caption>Destino y precio por minuto</caption>
					<thead><tr><td>Grupo</td><td>Descripcion</td><td>Prefijo</td><td>Coste(euros/min.)</td><td>Editar</td></thead><tbody>

					<?php while($row = mysql_fetch_assoc($registros))
					{ 
						if($colorfila%2==0)
						{
							echo "<tr><td>".$row['grupo']."</td><td>".$row['descripcion']."</td><td>".$row['prefijo']."</td><td>".$row['coste']."</td><td><a title='Editar Tarifa' href=\"editar_tarifa.php?usuario_troncal=".$troncal."&prefijo=".$row['prefijo']."&callcenter=".$callcenter."\">Editar</a></td></tr>";
					    }
						else
						{							
							echo "<tr class=\"odd\"><td>".$row['grupo']."</td><td>".$row['descripcion']."</td><td>".$row['prefijo']."</td><td>".$row['coste']."</td><td><a title='Editar Tarifa' href=\"editar_tarifa.php?usuario_troncal=".$troncal."&prefijo=".$row['prefijo']."&callcenter=".$callcenter."\">Editar</a></td></tr>";
						}	
						$colorfila++;		
					} 
	

		
					mysql_close($conn);	
?>		
								
						
					</tbody>
					</table>
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
           if ($page == 1)
           {
            ?>
              <li class="Anterior-off">&laquo; Anterior</li>
              <li class="active">1</li>
         <?php
              for($i= $page+1; $i<= $lastpage ; $i++)
              {?>
                <li><a href="tarifas_troncal.php?usuario_troncal=<?php echo $troncal;?>&callcenter=<?php echo $callcenter;?>&page=<?php echo $i;?>"><?php echo $i;?></a></li>
        <?php }
           
           //Y SI LA ULTIMA PÁGINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON Siguiente O LO DESHABILITO
            if($lastpage >$page )
            {?>     
                <li class="Siguiente"><a href="tarifas_troncal.php?usuario_troncal=<?php echo $troncal;?>&callcenter=<?php echo $callcenter;?>&page=<?php echo $Siguientepage;?>" >Siguiente &raquo;</a></li><?php
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
            <li class="Anterior"><a href="tarifas_troncal.php?usuario_troncal=<?php echo $troncal;?>&callcenter=<?php echo $callcenter;?>&page=<?php echo $prevpage;?>">&laquo; Anterior</a></li><?php
             for($i= 1; $i<= $lastpage ; $i++)
             {
                           //COMPRUEBO SI ES LA PÁGINA ACTIVA O NO
                if($page == $i)
                {
            ?>       <li class="active"><?php echo $i;?></li><?php
                }
                else
                {
            ?>       <li><a href="tarifas_troncal.php?usuario_troncal=<?php echo $troncal;?>&callcenter=<?php echo $callcenter;?>&page=<?php echo $i;?>" ><?php echo $i;?></a></li><?php
                }
            }
             //Y SI NO ES LA ÚLTIMA PÁGINA ACTIVO EL BOTON Siguiente    
            if($lastpage >$page )
            {   ?>  
                <li class="Siguiente"><a href="tarifas_troncal.php?usuario_troncal=<?php echo $troncal;?>&callcenter=<?php echo $callcenter;?>&page=<?php echo $Siguientepage;?>">Siguiente &raquo;</a></li><?php
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
}
 

 
?>
<p> &nbsp;</p>
<p> &nbsp;</p>
<p> &nbsp;</p>



					<!-- fin paginacion -->								
					
					
					<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
					</div>
					
					<form action="alta_tarifa.php" method="post" name="form_centralita" width="30%">
					<div align="center">
					
					<input type="hidden" size="53" name="usuario_troncal" value="<?php echo $troncal;?>"></td>
					<input type="hidden" size="53" name="form_callcenter" value="<?php echo $callcenter;?>"></td>
					
					<input type="button" value="Volver a Troncales"  onClick="location.href = 'troncales_callcenter.php?callcenter=<?php echo $callcenter; ?>'"  style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 
					<input type="submit" value="Añadir Tarifa a esta troncal" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	
					
					<input type="button" onclick="confirmar_eliminacion_todas_tarifas()" value="Eliminar TODAS Tarifas Asociadas" style=" width:90;height:90; background-image:url(./images/bg_button.gif)"> 	

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
