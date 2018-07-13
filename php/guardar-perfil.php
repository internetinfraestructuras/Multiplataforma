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
$telnet = new PHPTelnet();

check_session(3);


	// Check Action First!
	if(isset($_POST['action']) && $_POST['action'] == 'velocidades') {

        $a =$_POST['a'];
        $b =$_POST['b'];
        $c =$_POST['c'];
        $up =$_POST['up'];
        $dw =$_POST['dw'];


        $values = array($a, $b, $c, $_SESSION['USER_LEVEL'],$up, $dw);
        $campos=array('perfil_olt','nombre_perfil','id_olt','nivel_usuario','bytes_up','bytes_dw');

        $result = $util->insertInto('perfil_internet', $campos, $values);

//        $util->log('El administrador:'.$_SESSION['USER_ID'].' ha creado el revendedor:'.$dni.' con el resultado:'.$result);

//        leo informacion de la olt seleccionada para obtener la ip, usuario, clave y demas.
        $id_olt = $c;
        $cabeceras = $util->selectWhere('olts', $t_cabeceras, ' id=' . $id_olt);
        $row = mysqli_fetch_array($cabeceras);
        $server = $row['ip'];
        $user = $row['usuario'];
        $pass = $row['clave'];

        $aItems = array();

        $result = $telnet->Connect($server, $user, $pass);

        if ($result == 0) {
            $telnet->DoCommand('enable', $result);
            $telnet->DoCommand(PHP_EOL, $result);

            $telnet->DoCommand('config', $result);
            $telnet->DoCommand(PHP_EOL, $result);
            //echo 'interface gpon '.$c."/".$t;
            $cbs=(intval($up)*32)+2000;
            $pbs=(intval($dw)*32)+2000;


            $telnet->DoCommand('traffic table ip index '.$a.' name '.$a.' cir '.$up.' cbs '.$cbs.' pir '.$up.' pbs  '.$pbs.' priority 0 priority-policy tag-in-package', $result);


            $telnet->Disconnect();

        }

        echo true;
    }

    if(isset($_POST['action']) && $_POST['action'] == 'ont') {

        $a =$_POST['a'];
        $b =$_POST['b'];

        $values = array($a, $b);
        $campos=array('perfil','cabecera');

        $result = $util->insertInto('modelos_ont', $campos, $values);

        $util->log('El usuario:'.$_SESSION['USER_ID'].' ha creado el perfil de ont:'.$a.' con el resultado:'.$result);
        echo true;


    }



?>





