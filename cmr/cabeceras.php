<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 10:19
 */

require_once('util.php');
require_once('def_tablas.php');
$util = new util();

$id=$_POST['reseller'];
$token=$_POST['token'];
if(!$util->check_token($id,$token)) die("Error de token");

date_default_timezone_set('Etc/UTC');

/*
    // todo: --------------------------------------------
	// LISTAR LAS CABECERAS DE UN RESELLER
    // url: ftth.internetinfraestructuras.es/cmr/cabeceras.php
    // espera:
    //          $id=$_POST['reseller'];
    //          $token=$_POST['token'];

                                *
                * Devuelve:
                *       json:
                 *
                        'id' =>             id de la cabecera
                        'descripcion' =>    Texto descriptivo de la cabecera
                         'token' =>         Retorna el mismo token recibido

*/

$campos=array('id','descripcion');

$result = $util->selectWhere("olts", $campos, "wifero=".$_POST['reseller']);
$aItems = array();

while ($row = mysqli_fetch_array($result)) {
    $aItem = array(
        'id' => $row[0],
        'descripcion' => $row[1],
        'token' => $_POST['token']
    );
    array_push($aItems, $aItem);
}
header('Content-type: application/json; charset=utf-8');
echo json_encode($aItems);