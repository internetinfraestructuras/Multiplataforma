<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */

/*
    ╔════════════════════════════════════════════════════════════╗
    ║      Devuelve los atributos de un servicio       ║
    ╚════════════════════════════════════════════════════════════╝
*/
if (!isset($_SESSION))
{
    @session_start();
}
require_once ('../../clases/telefonia/classTelefonia.php');



//$util = new util();
//check_session(3);

$cifSuperUsuario='B45782687';

$telefonia=new Telefonia();
$gruposRecargas=$telefonia->getPaquetesDestino($cifSuperUsuario);
$grupos=array();
while ($row = mysqli_fetch_array($gruposRecargas))
{
    array_push($grupos, $row);
}
header('Content-type: application/json; charset=utf-8');
echo json_encode($grupos);


