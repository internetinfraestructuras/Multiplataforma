<?php
//Inicio la sesi�n
session_start();
//COMPRUEBA QUE EL USUARIO ESTA AUTENTICADO
if (!$_SESSION['autenticado']) {
//si no existe, va a la p�gina de autenticacion

header("Location: index.php");
//salimos de este script
exit();
}
?>