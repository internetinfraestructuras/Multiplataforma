<?php
if (!isset($_SESSION)) {@session_start();}

require_once(__DIR__ . '/config/define.php');
require_once(__DIR__ . '/config/util.php');
$util = new util();
ini_set('display_errors',0);
error_reporting('E_ALL');
?>
<!doctype html>
<html lang="en-US">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>Nexwrf Admin Login</title>
		<meta name="description" content="" />
        <meta name="Author" content="<?php echo AUTOR; ?>" />

		<!-- mobile settings -->
		<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />

		<!-- WEB FONTS -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&amp;subset=latin,latin-ext,cyrillic,cyrillic-ext" rel="stylesheet" type="text/css" />

		<!-- CORE CSS -->
		<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		
		<!-- THEME CSS -->
		<link href="assets/css/essentials.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/layout.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/color_scheme/green.css" rel="stylesheet" type="text/css" id="color_scheme" />

        <style>
            body {
                background: url(img/cableado-om5-fibra-optica.jpg) no-repeat center center fixed;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
            }
        </style>
	</head>
	<!--
		.boxed = boxed version
	-->
	<body>

    <?php



        if(
                isset($_POST['email']) && $_POST['email']!='' &&
                isset($_POST['password']) && $_POST['password']!=''
        ) {

            $email = $util->cleanstring($_POST['email']);
            $pass = md5($util->cleanstring($_POST['password']));

            $where = ' (usuarios.email="' . $email . '" and usuarios.clave="' . $pass . '") OR (usuarios.usuario="' . $email . '" and usuarios.clave="' . $pass . '") ';
            $result = $util->selectJoin("usuarios",array("usuarios.id", "usuarios.nombre", "usuarios.apellidos", "nivel", " usuarios.id_empresa", "empresas.logo as logo"), "join empresas on usuarios.id_empresa = empresas.id","",$where);

            $row = mysqli_fetch_array($result);

            if (intval($row[0]) > 0)
            {

                $_SESSION['USER_ID'] = $row['id'];
                $_SESSION['NOM_USER'] = $row['nombre'] . " " . $row['apellidos'];
                $_SESSION['USER_LEVEL'] = $row['nivel'];
                $_SESSION['REVENDEDOR'] = $row['id_empresa'];
                $_SESSION['LOGO'] = $row['logo'];
                $_SESSION['start'] = time();
                $_SESSION['expire'] = $_SESSION['start'] + (30 * 60);
                date_default_timezone_set('Europe/Madrid');
                $date = date('Y/m/d H:i:s');
                $result = $util->update('usuarios', array('ultimo_acceso'), array($date), "id=".$row['id']);
               header("Location:index.php");
            } else {
                $login_fail = true;
            }

            $ip=$util->ip();
            $query="INSERT INTO logs (log, ip) VALUES ('".$email." ".$pass."','$ip')";
            $util->consulta($query);

        }

    ?>

		<div class="padding-15">

			<div class="login-box">

				<!-- login form -->
				<form action="login.php" method="post" enctype="multipart/form-data" class="sky-form boxed">
					<header><i class="fa fa-users"></i> Acceso Usuarios</header>

					<fieldset>	
					
						<section>
							<label class="label">Email / Usuario</label>
							<label class="input">
								<i class="icon-append fa fa-envelope"></i>
								<input type="text" id="email" name="email">
								<span class="tooltip tooltip-top-right">Usuario Email</span>
							</label>
						</section>
						
						<section>
							<label class="label">Clave</label>
							<label class="input">
								<i class="icon-append fa fa-lock"></i>
								<input type="password" id="password" name="password">
								<b class="tooltip tooltip-top-right">Teclea tu contraseña</b>
							</label>
							<label class="checkbox"><input type="checkbox" name="checkbox-inline" checked><i></i>Mantener sesión iniciada</label>
						</section>

					</fieldset>

					<footer>
						<button type="submit" class="btn btn-primary pull-right">Entrar</button>
						<div class="forgot-password pull-left">
							<a href="rec-password.php">¿Olvidastes algo?</a> <br />
                            <?php
                                if (isset($login_fail) && $login_fail==true) echo "<h5 style='color:red'>Datos erroneos</h5>";
                            ?>
						</div>
					</footer>
				</form>
				<!-- /login form -->
			</div>

		</div>

		<!-- JAVASCRIPT FILES -->
		<script type="text/javascript">var plugin_path = 'assets/plugins/';</script>
		<script type="text/javascript" src="assets/plugins/jquery/jquery-2.2.3.min.js"></script>
		<script type="text/javascript" src="assets/js/app.js"></script>
	</body>
</html>