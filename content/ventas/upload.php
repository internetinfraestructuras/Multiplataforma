<?php
/**
 * // Upload Example
 *
 * $ds          = DIRECTORY_SEPARATOR;
 *
 * $storeFolder = 'uploads';
 *
 * if (!empty($_FILES)) {
 *
 * $tempFile = $_FILES['file']['tmp_name'];
 *
 * $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;
 *
 * $targetFile =  $targetPath. $_FILES['file']['name'];
 *
 * move_uploaded_file($tempFile,$targetFile);
 *
 * }
 **/
if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
$util = new util();

// solo los usuarios de nivel 3 a 0 pueden agregar clientes
check_session(3);


if (!empty($_FILES)) {
    $targetPath = '../../content/documentos/';
    $tempFile = $_FILES['file']['tmp_name'];
    $File = $_FILES['file']['name'];
    $ext = pathinfo($File, PATHINFO_EXTENSION);

    $fichero = $util->aleatorios(20,false).".".$ext;
    $targetFile = $targetPath . $fichero;
    move_uploaded_file($tempFile, $targetFile);
    $contador = intval($_COOKIE['contador']);
    $contador++;
    setcookie('contador', $contador, time()+7200);
    setcookie('documentos['.$contador.']', $fichero, time()+7200);

}
?>
