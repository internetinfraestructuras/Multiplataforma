<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */

/*
    ╔════════════════════════════════════════════════════════╗
    ║ Devuelve los datos de la cabecera indicada     ║
    ╚════════════════════════════════════════════════════════╝
*/

if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');
$util = new util();
check_session(3);


if(isset($_POST['cabecera']) && $_POST['cabecera']!='') {

    //              a       b       c       d       e       f       g               h       i       j           k       l          m            n
    $campos=array('olts.id','marca','modelo','ip','usuario','clave','descripcion','wifero','chasis','tarjeta','pon','id_inicial','serportini','config_acs.ip_radius',
        'config_acs.user_radius','config_acs.pass_radius','config_acs.usuario_web','config_acs.pass_web','config_acs.ssid','config_acs.vlan_acs','config_acs.dhcp_start',
    'config_acs.dhcp_end','config_acs.subnet','config_acs.ip_lan');

    $result = $util->selectJoin("olts", $campos, " LEFT JOIN config_acs ON config_acs.id_cabecera=olts.id ", "","olts.id=".$_POST['cabecera']);
    $aItems = array();

    while ($row = mysqli_fetch_array($result)) {
        $aItem = array(
            'a' => $row[0],
            'b' => $row[1],
            'c' => $row[2],
            'd' => $row[3],
            'e' => $row[4],
            'f' => $row[5],
            'g' => $row[6],
            'h' => $row[7],
            'i' => $row[8],
            'j' => $row[9],
            'k' => $row[10],
            'l' => $row[11],
            'm' => $row[12],
            'n' => $row[13],
            'o' => $row[14],
            'p' => $row[15],
            'q' => $row[16],
            'r' => $row[17],
            's' => $row[18],
            't' => $row[19],
            'u' => $row[20],
            'v' => $row[21],
            'w' => $row[22],
            'x' => $row[23]

        );
        array_push($aItems, $aItem);
    }


    //
//    id        a
//marca         b
//modelo        c
//ip            d
//usuario       e
//clave         f
//descripcion   g
//wifero        h
//chasis        i
//tarjeta       j
//pon           k
//id_inicial    l
//serportini    m
//id            13
//id_cabecera   14
//ip_radius     15  n
//user_radius   16  o
//pass_radius   17  p
//ssid          18  q
//usuario_web   19  r
//pass_web      20  s
//ip_lan        21  t
//dhcp_start    22  u
//dhcp_end      23  v
//subnet        24  w
//profile       26
//vlan_acs      27  x

    header('Content-type: application/json; charset=utf-8');
    echo json_encode($aItems);
}
