<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 06/04/2018
 * Time: 10:21
 */

if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');
$util = new util();
check_session(2);


if(isset($_POST['id']) && md5($_POST['id'])==$_POST['hash']){
    $descr= $_POST['resp'];
    $util->update('estado_olts',array('description'),array($descr),'id='.$_POST['id']);
}