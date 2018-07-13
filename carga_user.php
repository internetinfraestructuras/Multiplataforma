<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */
/*
    ╔════════════════════════════════════════════════════════════╗
    ║ Devuelve un listado de usuarios de la plataforma  ║
    ╚════════════════════════════════════════════════════════════╝
*/
if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');
$util = new util();
check_session(2);

$campos=array('usuarios.id','dni','usuarios.nombre','apellidos','email','telefono','tipos_usuarios.nombre','notas','fecha_alta','ultima_modificacion','ultimo_acceso','activo','usuario');

// si se recibe un id se lista los datos de ese registro
if(isset($_POST['id_usuario']) && $_POST['id_usuario']!='' && md5($_POST['id_usuario'])==$_POST['hash']){
    $campos=array('usuarios.id','dni','nombre','apellidos','email','telefono','nivel','notas','fecha_alta','ultima_modificacion','ultimo_acceso','activo','usuario');
    $result = $util->selectWhere("usuarios", $campos, ' usuarios.id=' . $_POST['id_usuario']);
} else { // si no se recibe id se listan todos

    if ($_SESSION['USER_LEVEL'] == 0)   // si es root, todos todos
        $result = $util->selectJoin("usuarios", $campos, " LEFT JOIN tipos_usuarios ON tipos_usuarios.nivel = usuarios.nivel ", "apellidos");
    else    // si no es root todos los del revendedor
        $result = $util->selectJoin("usuarios", $campos, " LEFT JOIN tipos_usuarios ON tipos_usuarios.nivel = usuarios.nivel ", "apellidos", ' revendedor=' . $_SESSION['REVENDEDOR']);
}
$aItems = array();

while ($row = mysqli_fetch_array($result)) {
    $aItem = array(
        'id' => $row[0],
        'dni'=> $row[1],
        'nombre'=> $row[2],
        'apellidos'=> $row[3],
        'email'=> $row[4],
        'tel1'=> $row[5],
        'nivel'=> $row[6],
        'notas'=> $row[7],
        'alta'=> $row[8],
        'modi'=> $row[9],
        'acceso'=> $row[10],
        'activo'=> $row[11],
        'usuario'=> $row[12]
    );
    array_push($aItems, $aItem);

}
header('Content-type: application/json; charset=utf-8');
echo json_encode($aItems);