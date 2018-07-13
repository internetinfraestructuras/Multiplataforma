<?php


//genera una cadena de 6 digitos aleatorios
function getRandomCode(){
    $an = "0123456789abcdefghijklmnopqrstuvwxyz";
    $su = strlen($an) - 1;
    return substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1);
}

function prefijoSuperusuario($usuario)
{
include ("configuracion.php");

	//conectamos a  MYSQL
	$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
	// Seleccionamos la Base de datos
	mysql_select_db($nombre_bd) or die(mysql_error());
	
	$sel ="select prefijo from superusuarios where superusuario='$usuario'";	
	$query = mysql_query($sel) or die(mysql_error());
	

		//Estoy mirando el saldo de un usuario normal
		$ret = mysql_fetch_array($query);  
        return $ret['prefijo']; 
	
	mysql_close($db);
		
		
}


function routerPassword($usuario)
{
include ("configuracion.php");

	//conectamos a  MYSQL
	$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
	// Seleccionamos la Base de datos
	mysql_select_db($nombre_bd) or die(mysql_error());
	
	$sel ="select routerPass from superusuarios where superusuario='$usuario'";	
	$query = mysql_query($sel) or die(mysql_error());
	

		//Estoy mirando el saldo de un usuario normal
		$ret = mysql_fetch_array($query);  
        return $ret['routerPass']; 
	
	mysql_close($db);
		
		
}					
					
function routerUsuario($usuario)
{
include ("configuracion.php");

	//conectamos a  MYSQL
	$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
	// Seleccionamos la Base de datos
	mysql_select_db($nombre_bd) or die(mysql_error());
	
	$sel ="select routerUsuario from superusuarios where superusuario='$usuario'";	
	$query = mysql_query($sel) or die(mysql_error());
	

		//Estoy mirando el saldo de un usuario normal
		$ret = mysql_fetch_array($query);  
        return $ret['routerUsuario']; 
	
	mysql_close($db);
		
		
}					
					
function routerIP($usuario)
{
include ("configuracion.php");

	//conectamos a  MYSQL
	$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
	// Seleccionamos la Base de datos
	mysql_select_db($nombre_bd) or die(mysql_error());
	
	$sel ="select router from superusuarios where superusuario='$usuario'";	
	$query = mysql_query($sel) or die(mysql_error());
	

		//Estoy mirando el saldo de un usuario normal
		$ret = mysql_fetch_array($query);  
        return $ret['router']; 
	
	mysql_close($db);
		
		
}

/*
alter table superusuarios add column routerClienteUsuario varchar(15);
alter table superusuarios add column routerClientePassword varchar(30);*/

function routerClienteUsuario($usuario)
{
include ("configuracion.php");

	//conectamos a  MYSQL
	$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
	// Seleccionamos la Base de datos
	mysql_select_db($nombre_bd) or die(mysql_error());
	
	$sel ="select routerClienteUsuario from superusuarios where superusuario='$usuario'";	
	$query = mysql_query($sel) or die(mysql_error());
	

		//Estoy mirando el saldo de un usuario normal
		$ret = mysql_fetch_array($query);  
        return $ret['routerClienteUsuario']; 
	
	mysql_close($db);
		
		
}

function routerClientePassword($usuario)
{
include ("configuracion.php");

	//conectamos a  MYSQL
	$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
	// Seleccionamos la Base de datos
	mysql_select_db($nombre_bd) or die(mysql_error());
	
	$sel ="select routerClientePassword from superusuarios where superusuario='$usuario'";	
	$query = mysql_query($sel) or die(mysql_error());
	

		//Estoy mirando el saldo de un usuario normal
		$ret = mysql_fetch_array($query);  
        return $ret['routerClientePassword']; 
	
	mysql_close($db);
		
		
}


function linkGraficaCliente($usuario)
{
include ("configuracion.php");

	//conectamos a  MYSQL
	$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
	// Seleccionamos la Base de datos
	mysql_select_db($nombre_bd) or die(mysql_error());
	
	$sel ="select linkGrafica from superusuarios where superusuario='$usuario'";	
	$query = mysql_query($sel) or die(mysql_error());
	

		//Estoy mirando el saldo de un usuario normal
		$ret = mysql_fetch_array($query);  
        return $ret['linkGrafica']; 
	
	mysql_close($db);
		
		
}


function puerto_acceso()
{

   return $_SERVER["SERVER_PORT"];
}


function obtener_direccion_ip()
{
	if($_SERVER["HTTP_X_FORWARDED_FOR"])
	{
		//echo "La Ip de tu proxy es:{$_SERVER["REMOTE_ADDR"]}<br>";
		//echo "Tu IP es:{$_SERVER["HTTP_X_FORWARDED_FOR"]}";
		return $_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	else{
		//echo "Tu IP es:{$_SERVER["REMOTE_ADDR"]}<br>";
		return $_SERVER["REMOTE_ADDR"];
	} 
}


function redondear_cinco_decimal($valor) {
   $float_redondeado=round($valor * 100000) / 100000;
   return $float_redondeado;
}



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

/* CAMBIADAAAAAAAAAAAAAAA
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
			mysql_close($db);	
        return $ret['saldo']; 
		
	} else 
	{
		//Estoy mirando el saldo de un super usuario => hago de nuevo la consulta
		$sel ="select saldo from superusuarios where superusuario='$usuario'";	
		$query = mysql_query($sel) or die(mysql_error());

		$ret = mysql_fetch_array($query); 
	mysql_close($db);			
        return $ret['saldo']; 
	}

	mysql_close($db);		

}*/

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

	mysql_close($db);		
	
	
	$var = " /usr/sendEmail/sendEmail -f plataforma.prepago@gmail.com -t ".$email." -s smtp.gmail.com -u ".$asunto." -v -o tls=yes -xu plataforma.prepago -xp gruporeq1430 -m ".$texto.".";

	exec($var);	
}


function mostrar_datos_user($usuario)
{
	echo "<div align=\"right\">";
	echo "<font size=\"2px\" color=\"#ff0000\" ><b>Usuario:&nbsp;</b></font><font size=\"2px\"><b>".$usuario."</b></font><br>";
	//echo "<font size=\"2px\" color=\"#ff8400\" ><b>Saldo:&nbsp;</b></font><font size=\"2px\">".redondear_tres_decimal(obtener_saldo($usuario))." €</font>";
	//echo "[".redondear_tres_decimal(obtener_saldo($usuario))." € ]";
	
	echo "</div>";
}
function menu_principal()
{
	
	$usuario=$_SESSION['nombre_usuario'];
	//echo "---------$usuario";
    ?>
				<h3 class="block-title"><span>Menú Principal</span></h3>
				<div style="border:1px solid gray; -moz-border-radius:10px; margin-left: 3em;margin-right: 3em; margin-top: 0.5em; padding: 0.5em; margin-bottom: 1em;">
				<ul class="list-matches" style='margin-left: 2em;' >
					<li><a href="principal.php" title="Principal"><span><strong>Principal</strong></span></a></li>
					<!--<li><a href="consultarsaldoactual.php"  title="Saldo"><span><strong>Consultar Saldo Actual</strong></span></a></li>
					<li><a href="tarifas_redistribucion.php"  title="Tarifas"><span><strong>Tarifas Redistribucion</strong></span></a></li>					
					<li><a href="historicorecargas.php"  title="Hist. Rec"><span><strong>Histórico Recargas</strong></span></a></li>	
					--><li><a href="clientes.php"  title="Clientes"><span><strong>Clientes</strong></span></a></li>		
					<li><a href="altacliente.php" title="Alta Cliente"><span><strong>Alta Cliente</strong></span></a></li>	
					<li><a href="<?php echo linkGraficaCliente($usuario);?>" title="Graf. Ancho Banda" target="_blank"><span><strong>Graf. Ancho Banda</strong></span></a></li>	
					<!--<li><a href="gruposderecarga.php" title="Grupos de Recarga"><span><strong>Grupos de Recarga</strong></span></a></li>											
					<li><a href="manual_adm_wholesale.pdf" target="_blank"  title="Manual Plataforma"><span><strong>Manual Plataforma</strong></span></a></li>
					--><li><a href="salir.php"  title="Salir"><span><strong>Salir</strong></span></a></li>
				</ul>
				</div>
	<?php	
}


function menu_principal_trucho()
{
    ?>
				<h3 class="block-title"><span>Menú Principal</span></h3>
				<ul class="list-matches" style="border:1px solid gray;  margin-left: 3em;margin-right: 3em; margin-top: 0.5em; padding: 0.5em; margin-bottom: 1em;" >
					<li><a href="principal.php" title="Principal"><span><strong>Principal</strong></span></a></li>
					<li><a href="consultarsaldoactual.php"  title="Saldo"><span><strong>Consultar Saldo Actual</strong></span></a></li>
					<li><a href="tarifas_redistribucion.php"  title="Tarifas"><span><strong>Tarifas Redistribucion</strong></span></a></li>					
					<li><a href="historicorecargas.php"  title="Hist. Rec"><span><strong>Histórico Recargas</strong></span></a></li>	
					<li><a href="clientes.php"  title="Clientes"><span><strong>Clientes</strong></span></a></li>		
					<li><a href="altacliente.php" title="Alta Cliente"><span><strong>Alta Cliente</strong></span></a></li>	
					<li><a href="estadotroncalesclientes.php" title="Estado Troncales"><span><strong>Estado Clientes</strong></span></a></li>						
					<li><a href="manual_adm_wholesale.pdf" target="_blank"  title="Manual Plataforma"><span><strong>Manual Plataforma</strong></span></a></li>
					<li><a href="salir.php"  title="Salir"><span><strong>Salir</strong></span></a></li>
				</ul>

	<?php	
}

function otros_servicios()
{
	?>
	<h3 class="block-title"><span>Otros servicios</span></h3>
		<div style="border:1px solid gray; -moz-border-radius:10px; margin-left: 3em;margin-right: 3em; margin-top: 0.5em; padding: 0.5em; margin-bottom: 1em;">
		<ul class="list-threads">
		
			<li><a href="http://www.reqfax.es" title="WEB FAX">Servicio de WEB FAX</a></li>
			<li><a href="http://www.gruporeq.eu" title="Alojamiento">Alojamiento WEB</a></li>
			<li><a href="http://www.reqluminaria.es/" title="Luminarias">REQ Luminiarias</a></li>
			<li><a href="http://www.gruporeq.eu" title="Internet">Internet Rural</a></li>
			<li><a href="http://www.gruporeq.eu" title="Localizadores">Localizadores</a></li>
		</ul>
		</div>
    <?php
}

function pie()
{
	?>	
		<div id="copyright">
			<p class="copyright-message">Nexwrf Copyright 2015, todos los derechos reservados</p>
			<p class="navigation"><a href="#"><font color="black">Soporte 956926969</font></a> : <a href="mailto:telefonia@gruporeq.com"><font color="black">Contacto</font></a></p>
		</div>
		<div id="credits">
			<p class="design-code"><span><font color="black">Diseñado por </font></span> <a href="http://www.nexwrf.es" title="Nexwrf" class="design-by"><font color="black">Nexwrf</font></a></p>
		
		

<p>

    <!--<a href="http://validator.w3.org/check?uri=referer"> -->
	<img src="images/valid-xhtml10.png"alt="Valid XHTML 1.0 Transitional" height="31" width="88"</a>
	<!--</a> -->

    <a href="http://jigsaw.w3.org/css-validator/check/referer">
        <img style="border:0;width:88px;height:31px"
            src="images/vcss.gif"
            alt="¡CSS Válido!" />
    </a>
</p>

<p>
<br>&nbsp;<br>
</p>
            
		
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

function title()
{
    ?>
	WISP Admin
	<?php
}
	
?>
