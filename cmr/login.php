<?php


/*
 * LOGIN
 * url:     http://ftth.internetinfraestructuras.es/cmr/login.php
 *
 * Espera:
 *
 *      $_POST['email']         String
 *      $_POST['password']      MD5
 *
 * Devuelve:
 *
*       'reseller' => Id del Reseller, que tendras que pasar en las proximas llamadas
        'nombre' => Nombre del reseller
        'apellidos' => Apellidos del Reseller
        'empresa' => empresa del Reseller
        'token'=>   token que tendras que pasar en las proximas llamadas

        --------------------PRUEBA POSTMAN ----------------------
    {
        "reseller": "1",
        "nombre": "Admin",
        "apellidos": "Nexwrf",
        "token": "c0c46029e40066c098b9913edb95c9bec0a6e5c76229743caf2511943f505b9a"
    }

 */

require_once( '../config/define.php');
require_once( '../config/util.php');
$util = new util();

if(
    (isset($_POST['email']) && $_POST['email']!='') &&
    (isset($_POST['password']) && $_POST['password']!='')
) {
    $email = $util->cleanstring($_POST['email']);
    $pass = $util->cleanstring($_POST['password']);
    $where = ' (usuarios.email="' . $email . '" and usuarios.clave="' . $pass . '") OR (usuarios.usuario="' . $email . '" and usuarios.clave="' . $pass . '") ';
    $result = $util->selectJoin("usuarios", array("usuarios.id", "usuarios.nombre", "usuarios.apellidos", "nivel", "usuarios.revendedor"), "join revendedores on usuarios.revendedor = revendedores.id","",$where);
    $query="INSERT INTO logs (log, ip) VALUES ('".$_POST['email']." ".$_POST['password']."','$ip')";
    $util->consulta($query);

    $token = bin2hex(openssl_random_pseudo_bytes(32));

    $aItems = array();

    $row = mysqli_fetch_array($result);
    if (intval($row[0]) > 0) {
        $aItem = array(
            'reseller' => $row['id'],
            'nombre' => $row['nombre'],
            'apellidos' => $row['apellidos'],
            'token' => $token
        );
        array_push($aItems, $aItem);

        date_default_timezone_set('Europe/Madrid');
        $date = date('Y/m/d H:i:s');
        $result = $util->update('usuarios', array('ultimo_acceso','hash'), array($date,$token), "id=".$row['id']);
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($aItems);
    } else {
        echo '_failed_';
    }
} else echo "faltan parametros";

/*
 * to-do:
 *
 * ALTER TABLE `usuarios` ADD `hash` VARCHAR(32) NOT NULL AFTER `usuario`;
 */
?>



