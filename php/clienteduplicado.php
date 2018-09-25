<?php

/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 19/09/2018
 * Time: 13:00
 */

/*
╔═════════════════════════════════════════════════════════════════════════╗
║ Devuelve los perfiles de velocidad de  la cabecera indicada  ║
╚═════════════════════════════════════════════════════════════════════════╝
*/

if (!isset($_SESSION)) {
    @session_start();
}

require_once('../config/util.php');
$util = new util();

$verificar = $_POST['verificar'];
$valor = $_POST['valor'];

if($verificar=='dni'){
    return $util->selectLast('clientes',
        "ID",
        "DNI = '".$valor."' AND ID_EMPRESA=".$_SESSION['REVENDEDOR']);

}  else if($valor=='email'){
    return $util->selectLast('clientes',
        "ID",
        "EMAIL = '".$valor."' AND ID_EMPRESA=".$_SESSION['REVENDEDOR']);
}

