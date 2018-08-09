<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */

/*
    ╔═════════════════════════════════════════════════════════════════════════════╗
    ║ Devuelve losperfiles pppoe de la mikrotik asociada a la cabecera ║
    ╚═════════════════════════════════════════════════════════════════════════════╝
*/

if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');
$util = new util();
check_session(3);
$campos=array('profile','descripcion');

if(isset($_POST['cabecera']) && $_POST['cabecera']!='') {

    $id_olt=$_POST['cabecera'];
    $result_acs = $util->selectWhere('config_acs', array('ip_radius', 'user_radius', 'pass_radius','profile'), ' id_cabecera=' . $id_olt);
    while ($row = mysqli_fetch_array($result_acs)) {
        $routerIP=$row['ip_radius'];
        $routerUsuario=$row['user_radius'];
        $routerPassword=$row['pass_radius'];
    }


    $aItems = array();
    if($routerIP!='' && $routerUsuario!= '' && $routerPassword!='' ) {
        require('clases/routeros_api.class.php');
        $API = new RouterosAPI();

//        echo $routerIP, $routerUsuario, $routerPassword;

        if ($API->connect($routerIP, $routerUsuario, $routerPassword)) {

            $ARRAY = $API->comm('/ppp/profile/getall');
            $c=0;
            foreach ($ARRAY as $clave) {
                if($clave['name']!='default') {
                    $aItem = array(
                        'perfil' => $clave['name'],
                        'descripcion' => $clave['name']
                    );
                    array_push($aItems, $aItem);
                    $c++;
                }
            }

            $API->disconnect();
            if($c==0){
                $aItem = array(
                    'perfil' => 'default',
                    'descripcion' => 'Perfiles no encontrado'
                );
                array_push($aItems, $aItem);
            }
        }
    } else {
        $aItem = array(
            'perfil' => 'default',
            'descripcion' => 'CCR NO configurado'
        );
        array_push($aItems, $aItem);
    }
//
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($aItems);
}
