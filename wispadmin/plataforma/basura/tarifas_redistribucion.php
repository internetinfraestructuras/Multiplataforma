<?php

include ("bloquedeseguridad.php");
include("funciones.php");
include ("configuracion.php");

$usuario=$_SESSION['nombre_usuario'];



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

					<h4 class="title"><?php echo "Tarifas autorizadas/minimas para distribuidor : $usuario"; ?></h4>			
			        <div align="center"> 
					<?php
		
					$colorfila=0;	
					
					$cif = cif_superusuario($usuario);	
					
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
					
				
$consulta="select * from tarifasminimassuperusuario_prueba where cif_super ='$cif' order by grupo";
$datos=mysql_query($consulta,$conn);
  
//MIRO CUANTOS DATOS FUERON DEVUELTOS
$num_rows=mysql_num_rows($datos);
  
//ACA SE DECIDE CUANTOS RESULTADOS MOSTRAR POR PÁGINA , EN EL EJEMPLO PONGO 15
$rows_per_page= 50;
  
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
		<caption>Destino y precio por minuto para distribuidor</caption>
		<thead><tr><td>Grupo</td><td>Descripcion</td><td>Prefijo</td><td>Coste(euros/min.)</td></thead><tbody>
		
		    <?php while($row = mysql_fetch_assoc($registros))
          { 
           
            if($colorfila%2==0)
						{
							echo "<tr><td>".$row['grupo']."</td><td>".$row['descripcion']."</td><td>".$row['prefijo']."</td><td>".$row['coste']."</td></tr>";
					    }
						else
						{							
							echo "<tr class=\"odd\"><td>".$row['grupo']."</td><td>".$row['descripcion']."</td><td>".$row['prefijo']."</td><td>".$row['coste']."</td></tr>";
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
                <li><a href="tarifas_redistribucion.php?page=<?php echo $i;?>"><?php echo $i;?></a></li>
        <?php }
           
           //Y SI LA ULTIMA PÁGINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON Siguiente O LO DESHABILITO
            if($lastpage >$page )
            {?>     
                <li class="Siguiente"><a href="tarifas_redistribucion.php?page=<?php echo $Siguientepage;?>" >Siguiente &raquo;</a></li><?php
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
            <li class="Anterior"><a href="tarifas_redistribucion.php?page=<?php echo $prevpage;?>">&laquo; Anterior</a></li><?php
             for($i= 1; $i<= $lastpage ; $i++)
             {
                           //COMPRUEBO SI ES LA PÁGINA ACTIVA O NO
                if($page == $i)
                {
            ?>       <li class="active"><?php echo $i;?></li><?php
                }
                else
                {
            ?>       <li><a href="tarifas_redistribucion.php?page=<?php echo $i;?>" ><?php echo $i;?></a></li><?php
                }
            }
             //Y SI NO ES LA ÚLTIMA PÁGINA ACTIVO EL BOTON Siguiente    
            if($lastpage >$page )
            {   ?>  
                <li class="Siguiente"><a href="tarifas_redistribucion.php?page=<?php echo $Siguientepage;?>">Siguiente &raquo;</a></li><?php
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
															
					</div>
					
					<div align="center">
					Para comercializar cualquier otro destino pongasé en contacto con el administrador de la plataforma.<br>
					Trás estudio comercial se establecerá la tarifa mínima.
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
