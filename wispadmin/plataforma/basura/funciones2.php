<?php


function redondear_tres_decimal($valor) {
   $float_redondeado=round($valor * 1000) / 1000;
   return $float_redondeado;
} 

//Mostramos el nombre de usuario
function mostrar_usuario()
{
	
	echo "<div align=\"right\">";
	echo "<font face=\"Verdana\" size=\"1\" color=\"#333366\">";
	echo "<b>Usuario: </b>";
	echo $_SESSION['nombre_usuario'];
	echo "&nbsp&nbsp&nbsp"; 
	echo "<a href=\"salir.php\">Salir</a>";
	echo "</font>";
	echo "<div align=\"right\">";
}

function mostrar_cabecera()
{
	echo "<table width=\"100%\" border=\"0\" bgcolor=\"#99ccff\">
  <tr>
    <td width=\"794\"><font face=\"Verdana\" size=\"2\" color=\"#333366\">Grupo REQ</font></span> </td>
    <td width=\"214\"  bgcolor=\"#FFFFFF\">";
	mostrar_usuario();
	echo"</td>
  </tr>
  <tr>
    <td colspan=\"2\">&nbsp</td>
  </tr>
</table>";
}

function mostrar_cabecera_sinuser()
{
	echo "<table width=\"100%\" border=\"0\" bgcolor=\"#99ccff\">
  <tr>
    <td><font face=\"Verdana\" size=\"2\" color=\"#333366\">Grupo REQ</font></span> </td>";
	echo"</td>
  </tr>
  <tr>
    <td colspan=\"2\">&nbsp</td>
  </tr>
</table>";
}

function obtener_saldo($usuario)
{
include ("configuracion.php");

	//conectamos a  MYSQL
	$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
	// Seleccionamos la Base de datos
	mysql_select_db($nombre_bd) or die(mysql_error());
	
	$sel ="select saldo from usuarios where usuario='$usuario'";	
	$query = mysql_query($sel) or die(mysql_error());
	
	if (mysql_num_rows($query)>0)
	{
		//Estoy mirando el saldo de un usuario normal
		$ret = mysql_fetch_array($query);  
        return $ret['saldo']; 
		
	} else 
	{
		//Estoy mirando el saldo de un super usuario => hago de nuevo la consulta
		$sel ="select saldo from superusuarios where superusuario='$usuario'";	
		$query = mysql_query($sel) or die(mysql_error());

		$ret = mysql_fetch_array($query);  
        return $ret['saldo']; 
	}

	mysql_close($db);		

}

function mostrar_datos_user($usuario)
{
	echo "<div align=\"right\">";
	echo "<font size=\"2px\" color=\"#ff8400\" ><b>Usuario:&nbsp;</b></font><font size=\"2px\"><b>".$usuario."</b></font><br>";
	//echo "<font size=\"2px\" color=\"#ff8400\" ><b>Saldo:&nbsp;</b></font><font size=\"2px\">".redondear_tres_decimal(obtener_saldo($usuario))." €</font>";
	echo "[".redondear_tres_decimal(obtener_saldo($usuario))." € ]";
	
	echo "</div>";
}

function menu_principal()
{
    ?>
				<h3 class="block-title"><span>Menu Principal</span></h3>
				<ul class="list-matches">
					<li class="game-cs16"><a href="principal.php" class="game-thumb" title="Saldo"><span><strong>Principal</a></li>
					<li class="game-cs16"><a href="consultarsaldoactual.php" class="game-thumb" title="Saldo"><span><strong>Consultar Saldo Actual</a></li>
					<li class="game-cs16"><a href="superusuarios.php" class="game-thumb" title="superusuarios"><span><strong>Superusuarios</a></li>	
					<li class="game-cs16"><a href="altasuperusuario.php" class="game-thumb" title="Franja"><span><strong>Alta Superuser</a></li>	
					<li class="game-cs16"><a href="clientes.php" class="game-thumb" title="Franja"><span><strong>Clientes</a></li>		
					<li class="game-cs16"><a href="altacliente.php" class="game-thumb" title="Franja"><span><strong>Alta Cliente</a></li>	
					<li class="game-cs16"><a href="troncalesydesbloquear.php" class="game-thumb" title="Desbloquear"><span><strong>Troncales/Callcenters</a></li>		
					<li class="game-cs16"><a href="salir.php" class="game-thumb" title="salir"><span><strong>Salir</a></li>
				</ul>
	<?php	
}

function otros_servicios()
{
	?>
	<h3 class="block-title"><span>Otros servicios de Grupo REQ</span></h3>
		<ul class="list-threads">
		
			<li><a href="http://www.reqfax.es" title="WEB FAX">Servicio de WEB FAX</a></li>
			<li><a href="http://www.gruporeq.eu" title="Alojamiento">Alojamiento WEB</a></li>
			<li><a href="http://www.reqluminaria.es/" title="Luminarias">REQ Luminiarias</a></li>
			<li><a href="http://www.gruporeq.eu" title="Internet">Internet Rural</a></li>
			<li><a href="http://www.gruporeq.eu" title="Localizadores">Localizadores</a></li>
		</ul>
    <?php
}

function pie()
{
	?>	
		<div id="copyright">
			<p class="copyright-message">Grupo REQ Copyright 2011, todos los derechos reservados</p>
			<p class="navigation"><a href="#"><font color="black">Soporte 956926969</font></a> : <a href="mailto:telefonia@gruporeq.com"><font color="black">Contacto</font></a></p>
		</div>
		<div id="credits">
			<p class="design-code"><span><font color="black">Diseñado por </font></span> <a href="http://www.gruporeq.eu/" title="Grupo REQ" class="design-by"><font color="black">Grupo REQ</font></a></p>
		</div>
    <?php
}

function bloque_sponsor()
{
    ?>
	    <h3 class="block-title-pakito"><span></h3>
		<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;
		<!--<div class="sponsor-image"><a href="http://www.gruporeq.eu/" title="gruporeq.eu"><img src="images/sponsors/clantemplates.png" alt=" " /></a></div>-->
	<?php				
}

function obtener_superusuario_usuario($usu)
{
	include ("configuracion.php");

	//conectamos a  MYSQL
	$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
	// Seleccionamos la Base de datos
	mysql_select_db($nombre_bd) or die(mysql_error());
	
	$sel = "select cif_super from usuarios where cif='$usu'";
	$query = mysql_query($sel) or die(mysql_error());
					
	$ret = mysql_fetch_array($query);  
	return $ret['cif_super']; 
}

function nombre_centralita($id_centralita)
{
include ("configuracion.php");

	//conectamos a  MYSQL
	$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
	// Seleccionamos la Base de datos
	mysql_select_db($nombre_bd) or die(mysql_error());
	
	$sel ="select nombre from centralitas where id_centralita='$id_centralita'";	
	$query = mysql_query($sel) or die(mysql_error());
	

		$ret = mysql_fetch_array($query);  
        	mysql_close($db);		
		return $ret['nombre']; 
		

}


function title()
{
    ?>
	Plataforma Prepago Grupo REQ
	<?php
}


function enviar_mail_usuario($usuario,$asunto,$texto)
{
include ("configuracion.php");
	
	//obtenemos la direccion de correo del superusuario o usuario
	
	//conectamos a  MYSQL
	$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
	// Seleccionamos la Base de datos
	mysql_select_db($nombre_bd) or die(mysql_error());
	
	$sel ="select email from usuarios where usuario='$usuario'";	
	$query = mysql_query($sel) or die(mysql_error());
	
	if (mysql_num_rows($query)>0)
	{
		//Estoy mirando el email de un usuario normal
		$ret = mysql_fetch_array($query);  
			mysql_close($db);	
        $email=$ret['email']; 
		
	} else 
	{
		//Estoy mirando el email de un super usuario => hago de nuevo la consulta
		$sel ="select email from superusuarios where superusuario='$usuario'";	
		$query = mysql_query($sel) or die(mysql_error());
		
		$ret = mysql_fetch_array($query);  
			mysql_close($db);	
        $email=$ret['email']; 
	}

	mysql_close($db);		
	
	
	$var = " /usr/sendEmail/sendEmail -f plataforma.prepago@gmail.com -t ".$email." -s smtp.gmail.com -u ".$asunto." -v -o tls=yes -xu plataforma.prepago -xp gruporeq1430 -m ".$texto.".";

	exec($var);	
}


function enviar_mail_usuario_cif($cif,$asunto,$texto)
{
include ("configuracion.php");
	
	//obtenemos la direccion de correo del superusuario o usuario
	
	//conectamos a  MYSQL
	$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
	// Seleccionamos la Base de datos
	mysql_select_db($nombre_bd) or die(mysql_error());
	
	$sel ="select email from usuarios where cif='$cif'";	
	$query = mysql_query($sel) or die(mysql_error());
	
	if (mysql_num_rows($query)>0)
	{
		//Estoy mirando el email de un usuario normal
		$ret = mysql_fetch_array($query);  
			mysql_close($db);	
        $email=$ret['email']; 
		
	} else 
	{
		//Estoy mirando el email de un super usuario => hago de nuevo la consulta
		$sel ="select email from superusuarios where cif_superuser='$cif'";	
		$query = mysql_query($sel) or die(mysql_error());
		
		$ret = mysql_fetch_array($query);  
			mysql_close($db);	
        $email=$ret['email']; 
	}
	
	
	$var = " /usr/sendEmail/sendEmail -f plataforma.prepago@gmail.com -t ".$email." -s smtp.gmail.com -u ".$asunto." -v -o tls=yes -xu plataforma.prepago -xp gruporeq1430 -m ".$texto.".";

	exec($var);	
}

	
function cif_superusuario($usuari)
{
include ("configuracion.php");

	//conectamos a  MYSQL
	$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
	// Seleccionamos la Base de datos
	mysql_select_db($nombre_bd) or die(mysql_error());
	
	$sel ="select cif_superuser from superusuarios where superusuario='$usuari'";	
	$query = mysql_query($sel) or die(mysql_error());
	
	$ret = mysql_fetch_array($query);  
	mysql_close($db);	
    return $ret['cif_superuser']; 

}	
	
	
?>
