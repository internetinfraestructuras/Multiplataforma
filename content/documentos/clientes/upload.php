<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 09/10/2018
 * Time: 8:11
 */

if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../../config/util.php');
$util = new util();

// solo los usuarios de nivel 3 a 0 pueden agregar clientes
check_session(3);

//upload.php
if($_FILES["file"]["name"] != '')
{
    $test = explode('.', $_FILES["file"]["name"]);
    $tipo = $_POST['tipodoc'];
    $ext = end($test);
    $name = $util->aleatorios(20,false) .".". $ext;
    $location =  $name;
    move_uploaded_file($_FILES["file"]["tmp_name"], $location);
    echo $name;
}
?>