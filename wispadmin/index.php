<?php

include("plataforma/funciones.php");

//si vengo por el puerto 80 => me muevo al 443
/*if(puerto_acceso()==80)
{
        header( "HTTP/1.1 301 Moved Permanently" );
        header("Location: https://www.voipreq.com/wholesale"); exit;
}*/



?>

<head>
<title>
   WISP Admin
</title>
</head>
<!-- Plataforma creada y mantenida por Francisco Perez Cortejosa paco.perez@gruporeq.com -->

<frameset rows="0,*" framespacing="0" border="0" frameborder="no">
<frame name="invisible" src="blanco.html" scrolling="no" marginwidth="0" marginheight="0" noresize frameborder="no">
<frame name="contenidoreq" src="plataforma/index.php" marginwidth="0" marginheight="0" noresize frameborder="no">
<noframes>

</noframes>
</frameset> 
