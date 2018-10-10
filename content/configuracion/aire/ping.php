<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 25/09/2018
 * Time: 16:45


    ╔═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╗
    ║ BLOQUEO/DESBLOQUEOS DE LÍNEAS ACTIVAS EN MAS MOVIL                                                               ║
    ╚═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╝
*/


if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../../config/util.php');
require_once('../../../clases/airenetwork/clases/Cliente.php');
require_once('../../../clases/Contrato.php');
require_once('../../../clases/Servicio.php');

$util = new util();
check_session(1);

$usuario=$util->cleanstring($_POST['usuario']);
$pass=$util->cleanstring($_POST['pass']);
$url=$util->cleanstring($_POST['url']);


$apiAire=new Cliente($url,$usuario,$pass);
$r=$apiAire->getClientesPaginados(1,1);

if($r==PETICION_AIRE_PASS_INCORRECTA)
    echo "Error, revise los parámetros de conexión establecidos";
else
    echo "Conexión establecida correctamente, no olvide de guardar la configuración pulsando VALIDAR Y GUARDAR!!!"




?>



