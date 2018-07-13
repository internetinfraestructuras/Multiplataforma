<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 10:19
 */
if (!isset($_SESSION)) {
    @session_start();
}

require_once('../config/util.php');
require_once('../config/def_tablas.php');
$util = new util();
check_session(0);

date_default_timezone_set('Etc/UTC');



ini_set('max_execution_time', 1600);
ini_set('memory_limit', 1024 * 1024);

$n=$_POST['ipccr'];
$o=$_POST['userapi'];
$pacs=$_POST['claveapi'];
$q=$_POST['useront'];
$r=$_POST['passont'];
$s=$_POST['ssid'];
$tacs=$_POST['vlanacs'];
$u=$_POST['dhcpini'];
$v=$_POST['dhcpfin'];
$w=$_POST['mascara'];
$x=$_POST['lanip'];

	// Check Action First!
	if(isset($_POST['action']) && $_POST['action'] == 'cabeceras') {


        $array = $required = array();

        // catch post data
        $post_data = isset($_POST['cabeceras']) ? $_POST['cabeceras'] : null;
        $is_ajax = (isset($_POST['is_ajax']) && $_POST['is_ajax'] == 'true') ? true : false;

        // check post data
        if ($post_data === null) {
            if ($is_ajax === false) {
                _redirect('#alert_mandatory');
            } else {
                die('_mandatory_');
            }
        }


        // EXTRACT DATA FROM POST
        foreach ($post_data as $key => $value) {
            $key_title = ucfirst($key);

            $explode = @explode('_', $key_title);
            if (!isset($explode[1]))
                $explode = @explode('-', $key_title);

            if (isset($explode[1])) {
                $key_title = implode(' ', $explode);
                $key_title = ucwords(strtolower($key_title));
            }

            $marca = $util->cleanstring($post_data['marca']);
            $modelo = $util->cleanstring($post_data['modelo']);
            $descripcion = $util->cleanstring($post_data['descripcion']);
            $ip = $post_data['ip'];
            $user = $post_data['user'];
            $pass = $post_data['pass'];
            $revendedor = $util->cleanstring($post_data['revendedor']);
            $c = $util->cleanstring($post_data['c']);
            $t = $util->cleanstring($post_data['t']);
            $p = $util->cleanstring($post_data['p']);
            $idini = $util->cleanstring($post_data['idini']);
            $serini = $util->cleanstring($post_data['serviceport']);
        }


        $values = array($marca, $modelo,$ip,$user,$pass,$descripcion, $revendedor,$c,$t,$p,$idini, $serini );
        $campos=array('marca','modelo','ip','usuario','clave','descripcion','wifero','chasis','tarjeta','pon','id_inicial','serportini');

        $last_id = $util->insertInto('olts', $campos, $values);
        $idolt=$last_id;
        $util->log('El administrador:'.$_SESSION['USER_ID'].' ha creado la cabecera:'.$ip.' con el resultado:'.$last_id);


        if(intval($last_id)>0){

            $values = array($last_id, $n,$o,$pacs,$s,$q,$r,$x,$u,$v,$w,$tacs);
            $campos=array('id_cabecera','ip_radius','user_radius','pass_radius','ssid','usuario_web','pass_web','ip_lan',
                'dhcp_start','dhcp_end','subnet','vlan_acs');
            $util->insertInto('config_acs', $campos, $values);

            $campos=array('nombre_perfil', 'perfil_olt', 'nivel_usuario', 'id_olt', 'bytes_up', 'bytes_dw');
            $a_lineprof = $util->selectWhere('lineprofiles',$campos);
            while ($row = mysqli_fetch_array($a_lineprof)){
                $values = array($row[0],$row[1],$row[2],$idolt,$row[4],$row[5]);
                $result = $util->insertInto('perfil_internet', $campos, $values);
            }

            $a_srvprof = $util->selectWhere('srvprofilesname',array('profilename'));
            while ($row = mysqli_fetch_array($a_srvprof)){
                $modelo = $row[0];
                $campos=array('perfil','cabecera');
                $values=array($modelo,$idolt);
                $result = $util->insertInto('modelos_ont', $campos, $values);

            }


            $telnet = new PHPTelnet();
            $r = $telnet->Connect($ip, $user, $pass);
            if ($r == 0) {
                $telnet->DoCommand('enable', $result);
                $telnet->DoCommand(PHP_EOL, $result);
                $telnet->DoCommand('config', $result);
                $telnet->DoCommand(PHP_EOL, $result);
                $re = $util->selectWhere('comandosiniciales',array('comando'));

                while ($row = mysqli_fetch_array($re)){
                    $respuesta_olt=null;
                    $telnet->DoCommand($row[0].PHP_EOL.PHP_EOL, $respuesta_olt);
                    $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $row[0] . "','" . $respuesta_olt . "','" . $id_olt . "');");
                }

//                $a_srvprof = $util->selectWhere('srvprofilesname','profilename');
//                while ($row = mysqli_fetch_array($a_srvprof)){
//
//                }


            }

            if($is_ajax === false) {
                _redirect('#alert_success');
                exit;
            } else {
                die('_success_');
            }
        } else{
            if($is_ajax === false) {
                _redirect('#alert_failed');
                exit;
            } else {
                die('_failed_');
            }
        }

    }

    if(
        (isset($_POST['oper']) && $_POST['oper'] == 'edit')
        &&
        (isset($_POST['id']) && $_POST['id'] != '')
    )
    {

        $marca = $util->cleanstring($_POST['marca']);
        $modelo = $util->cleanstring($_POST['modelo']);
        $descripcion = $util->cleanstring($_POST['descripcion']);
        $ip = $_POST['ip'];
        $user = $_POST['user'];
        $pass = $_POST['pass'];
        $revendedor = $util->cleanstring($_POST['revendedor']);
        $c =  $_POST['c'];
        $t =  $_POST['t'];
        $p =  $_POST['p'];
        $idini =  $_POST['idini'];
        $id =  $_POST['id'];
        $serviceport =  $_POST['serviceport'];




        $values = array($marca, $modelo,$ip,$user,$pass,$descripcion, $revendedor, $c, $t,$p, $idini, $serviceport );
        $campos=array('marca','modelo','ip','usuario','clave','descripcion','wifero','chasis','tarjeta','pon','id_inicial','serportini');

        $result = $util->update('olts', $campos, $values, "id='$id'");
        $util->log('El administrador:'.$_SESSION['USER_ID'].' ha modificado el revendedor:'.$dni.' con el resultado:'.$result);

        $idcabecera=$_POST['id'];

        $values = array($n,$o,$pacs,$s,$q,$r,$x,$u,$v,$w,$tacs);
        $campos=array('ip_radius','user_radius','pass_radius','ssid','usuario_web','pass_web','ip_lan','dhcp_start','dhcp_end','subnet','vlan_acs');

        $result = $util->update('config_acs', $campos, $values, " id_cabecera=$idcabecera");


        if(intval($result)>0){
                _redirect('#alert_success');

        } else {
            _redirect('#alert_failed');
        }
    }

function _redirect($hash) {

    $HTTP_REFERER = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

    if($HTTP_REFERER === null)
        die("Invalid Referer. Output Message: {$hash}");

    header("Location: {$HTTP_REFERER}{$hash}");
    exit;
}
/*
 * 1	idPrimaria	int(11)			No	Ninguna		AUTO_INCREMENT
2	id_cabecera	int(5)			No	Ninguna
3	ip_radius	varchar(15)	utf8_spanish2_ci		No	Ninguna
4	user_radius	varchar(30)	utf8_spanish2_ci		No	Ninguna
5	pass_radius	varchar(30)	utf8_spanish2_ci		No	Ninguna
6	ssid	varchar(40)	utf8_spanish2_ci		No	Ninguna
7	usuario_web	varchar(20)	utf8_spanish2_ci		No	Ninguna
8	pass_web	varchar(20)	utf8_spanish2_ci		No	Ninguna
9	ip_lan	varchar(15)	utf8_spanish2_ci		No	Ninguna
10	dhcp_start	varchar(15)	utf8_spanish2_ci		No	Ninguna
11	dhcp_end	varchar(15)	utf8_spanish2_ci		No	Ninguna
12	subnet	varchar(15)	utf8_spanish2_ci		No	Ninguna
13	profile	varchar(30)	utf8_spanish2_ci		No	Ninguna
14	vlan_acs	varchar(3)	utf8_spanish2_ci		No	Ninguna
 */
?>


