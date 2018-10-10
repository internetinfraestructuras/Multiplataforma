<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 09/10/2018
 * Time: 11:28
 */

require_once('../config/util.php');
$util = new util();
check_session(3);


if(isset($_POST['documento']) && $_POST['documento']!='') {

    unlink("../".$_POST['documento']);
    $util->consulta("DELETE FROM clientes_documentos WHERE DOCUMENTO ='".$_POST['documento']."'");
}

