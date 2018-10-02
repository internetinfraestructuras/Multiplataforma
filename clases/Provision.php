<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 01/10/2018
 * Time: 8:07
 */


/*
    ╔═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╗
    ║ Para cambiar la velocidad de una provision tengo que borrarle el service port y crearlo de nuevo                 ║
    ╚═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╝
*/


require_once('../../config/util.php');


class Provision
{
    public $util, $telnet;

    public function __construct()
    {

        if (!isset($_SESSION)) {
            @session_start();
        }


        $this->telnet = new PHPTelnet();
        $this->util = new util();
//        check_session(2);


        ini_set('max_execution_time', 300);
        ini_set('memory_limit', 1024 * 1024);

    }

    public function cambiarVelocidad($pon, $up, $dw)
    {

        $campos = array('id', 'c', 't', 'p', 'id_en_olt', 'id_internet', 'cabecera');
        $provision = $this->util->selectWhere3(FIBRADB . 'aprovisionados', $campos, ' num_pon="' . $pon . '"', ' id DESC');

        if (intval($provision[0]) > 0) {
            $cabecera = $this->util->selectWhere(FIBRADB . 'olts', array('ip', 'usuario', 'clave'), ' id=' . $provision[0]['cabecera']);
            $row = mysqli_fetch_array($cabecera);
            $server = $row['ip'];
            $user = $row['usuario'];
            $pass = $row['clave'];
        }

        $serviceport = $provision[0][5];

        $ont_id = $provision[0][4];
        $gpon = $provision[0][1] . "/" . $provision[0][2] . "/" . $provision[0][3];

        $respuesta_olt = $this->telnet->Connect($server, $user, $pass);

        if ($respuesta_olt == 0) {
            $this->telnet->DoCommand('enable', $void);
            $this->telnet->DoCommand(PHP_EOL, $void);

            $this->telnet->DoCommand('config', $void);
            $this->telnet->DoCommand(PHP_EOL, $void);

            $respuesta_olt = "";
            /*
            ╔═════════════════════════════════════╗
            ║ Borro el servicio de internet  ║
            ╚═════════════════════════════════════╝
            */
            $this->telnet->DoCommand("undo service-port " . $serviceport . PHP_EOL . PHP_EOL, $void);
            /*
            ╔═════════════════════════════════════╗
            ║ Creo el servicio de internet   ║
            ╚═════════════════════════════════════╝
            */
            $comando1 = "service-port " . $serviceport . " vlan 100 gpon " . $gpon . " ont " . $ont_id . " gemport 1 multi-service user-vlan 100 tag-transform translate inbound traffic-table index " . $up . " outbound traffic-table index " . $dw . " " . PHP_EOL;

            $this->telnet->DoCommand($comando1 . PHP_EOL . PHP_EOL, $respuesta_olt);

            /*
           ╔═════════════════════════════════════╗
           ║ Compruebo que se creó bien     ║
           ╚═════════════════════════════════════╝
           */
            $responder = "ok";

            if (strpos($respuesta_olt, 'The traffic table does not exist') > 0) {
                $responder = "Error: El / Los perfiles de velocidad no existen en la cabecera";
                $err_num = 3;
            } else if (strpos($respuesta_olt, 'does not exist') > 0 || strpos($respuesta_olt, 'Parameter error') > 0) {
                $responder = "Error: El / Los perfiles de velocidad no existen en la cabecera";
                $err_num = 3;
            } else
                $this->util->consulta("UPDATE " . FIBRADB . "aprovisionados SET velocidad_up='" . $up . "',velocidad_dw='" . $dw . "' WHERE id = " . $provision[0][0]);

//            $this->util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando1 . "','" . $respuesta_olt . "','" . $id_olt . "');");

            $this->telnet->DoCommand("quit" . PHP_EOL, $void);
            $this->telnet->Disconnect();

            return $responder;
        }
    }

    public function listarVelocidades($pon)
    {

        $campos = array('nombre_perfil', 'perfil_olt');


        $result = $this->util->selectWhere(FIBRADB . "perfil_internet", $campos,
            "id_olt=( SELECT cabecera FROM " . FIBRADB . "aprovisionados WHERE num_pon='" . $pon . "' order by id desc limit 1)");

        $aItems = array();

        while ($row = mysqli_fetch_array($result)) {
            $aItem = array(
                'front' => $row[0],
                'back' => $row[1]
            );
            array_push($aItems, $aItem);
        }

        return $aItems;


    }

    public function bajaTotal($pon)
    {
        $campos = array('id', 'c', 't', 'p', 'id_en_olt', 'id_internet', 'id_voip', 'id_iptv', 'id_vpn', 'id_acs', 'cabecera');
        $provision = $this->util->selectWhere3(FIBRADB . 'aprovisionados', $campos, ' num_pon="' . $pon . '"', ' id DESC');

        if (intval($provision[0]) > 0) {
            $cabecera = $this->util->selectWhere(FIBRADB . 'olts', array('ip', 'usuario', 'clave'), ' id=' . $provision[0]['cabecera']);
            $row = mysqli_fetch_array($cabecera);
            $server = $row['ip'];
            $user = $row['usuario'];
            $pass = $row['clave'];
        }

        $idinternet = $provision[0][5];
        $idvoz = $provision[0][6];
        $idtv = $provision[0][7];
        $idvpn = $provision[0][8];
        $idacs = $provision[0][9];

        $idont = $provision[0][4];
        $gpon = $provision[0][1] . "/" . $provision[0][2] . "/" . $provision[0][3];
        $c = $provision[0][1];
        $t = $provision[0][2];
        $p = $provision[0][3];
        $id_olt = $provision[0]['cabecera'];

        $result = $this->telnet->Connect($server, $user, $pass);

        if ($result == 0) {
            $this->telnet->DoCommand('enable', $result);
            $this->telnet->DoCommand(PHP_EOL, $result);

            $this->telnet->DoCommand('config', $result);
            $this->telnet->DoCommand(PHP_EOL, $result);

            /*
          ╔═══════════════════════════════════════════╗
          ║ Resetear la ont a fabrica para poder║
            aprovisionarla de neuvo si es necesario
          ╚═══════════════════════════════════════════╝
          */

            $this->telnet->DoCommand('interface gpon ' . $c . '/' . $t . PHP_EOL, $result);
            $this->telnet->DoCommand(PHP_EOL, $result);
            $this->util->log('interface gpon ' . $c . "/" . $t . " " . $result . $id_olt);
            //$telnet->DoCommand('clear' . PHP_EOL, $void);

            $this->telnet->DoCommand('ont factory-setting-restore ' . $p . ' ' . $idont . PHP_EOL . "y" . PHP_EOL, $result);


            $this->telnet->DoCommand('quit' . PHP_EOL . PHP_EOL, $void);

            /*
          ╔═════════════════════════════════════╗
          ║ Borra el servicio de internet  ║
          ╚═════════════════════════════════════╝
          */
            if (intval($idinternet) > 0) {
                $this->telnet->DoCommand('undo service-port ' . $idinternet . PHP_EOL, $result);

            }

            /*
          ╔═════════════════════════════════════╗
          ║ Borra el servicio de tv        ║
          ╚═════════════════════════════════════╝
          */
            if (intval($idtv) > 0) {

                $this->telnet->DoCommand('undo service-port ' . $idtv . PHP_EOL, $result);
                $this->util->log('undo service-port ' . $idtv . " " . $result . " " . $id_olt);

            }

            /*
          ╔═════════════════════════════════════╗
          ║ Borra el servicio de Voz       ║
          ╚═════════════════════════════════════╝
          */
            if (intval($idvoz) > 0) {

                $this->telnet->DoCommand('undo service-port ' . $idvoz . PHP_EOL, $result);
                $this->util->log('undo service-port ' . $idvoz . " " . $result . " " . $id_olt);

            }
            /*
          ╔═════════════════════════════════════╗
          ║ Borra el servicio de VPn       ║
          ╚═════════════════════════════════════╝
          */
            if (intval($idvpn) > 0) {

                $this->telnet->DoCommand('undo service-port ' . $idvpn . PHP_EOL, $result);


            }

            /*
          ╔═════════════════════════════════════╗
          ║ Borra el servicio de ACS       ║
          ╚═════════════════════════════════════╝
          */
            if (intval($idacs) > 0) {

                $this->telnet->DoCommand('undo service-port ' . $idacs . PHP_EOL, $result);

            }

            /*
          ╔═══════════════════════════════════════════╗
          ║ Cambia la interface y Borra la ont  ║
          ╚═══════════════════════════════════════════╝
          */

            $this->telnet->DoCommand('interface gpon ' . $c . '/' . $t . PHP_EOL . PHP_EOL, $result);

            $this->telnet->DoCommand('ont delete ' . $p . ' ' . $idont . PHP_EOL . "y" . PHP_EOL, $result);

            /*
           ╔═══════════════════════════════════════════════════════════════════╗
           ║ Borra la ont del servidor acs para poder reutilizarla.  ║
           ╚═══════════════════════════════════════════════════════════════════╝
           */

            $result = $this->util->selectWhere('acs_ids', array('id_acs'), " pon='" . $pon . "'");

            while ($row = mysqli_fetch_array($result)) {
                $id_device = $row[0];
            }
            if ($id_device == '')
                $id_device = '00259E-HG8546M-' . $pon;

            $url = "http://10.211.2.2:7557/devices/" . $id_device . "/";

            $ch = curl_init();
            $default_curl_options = array(
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HEADER => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10,
            );

            curl_setopt_array($ch, $default_curl_options);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");


            /*
           ╔═══════════════════════════════════════════════════════╗
           ║Borra el registro de la tabla aprovisionados   ║
           ╚═══════════════════════════════════════════════════════╝
           */
            $q = "DELETE FROM aprovisionados WHERE id='" . $_POST['p'] . "';";
            $this->util->consulta($q);
            $this->telnet->Disconnect();


        }

    }

    public function bajaServicios($pon,$internet=false, $voz=false, $tv=false)
    {
        $campos = array('id', 'c', 't', 'p', 'id_en_olt', 'id_internet', 'id_voip', 'id_iptv', 'id_vpn', 'id_acs', 'cabecera');
        $provision = $this->util->selectWhere3(FIBRADB . 'aprovisionados', $campos, ' num_pon="' . $pon . '"', ' id DESC');
        var_dump($provision);
        if (intval($provision[0]) > 0) {
            $cabecera = $this->util->selectWhere(FIBRADB . 'olts', array('ip', 'usuario', 'clave'), ' id=' . $provision[0]['cabecera']);
            $row = mysqli_fetch_array($cabecera);
            $server = $row['ip'];
            $user = $row['usuario'];
            $pass = $row['clave'];
            var_dump($cabecera);
        }

        $idinternet = $provision[0][5];
        $idvoz = $provision[0][6];
        $idtv = $provision[0][7];
        $idvpn = $provision[0][8];
        $idacs = $provision[0][9];

        $idont = $provision[0][4];
        $gpon = $provision[0][1] . "/" . $provision[0][2] . "/" . $provision[0][3];
        $c = $provision[0][1];
        $t = $provision[0][2];
        $p = $provision[0][3];
        $id_olt = $provision[0]['cabecera'];

        $result = $this->telnet->Connect($server, $user, $pass);

        if ($result == 0) {
            $this->telnet->DoCommand('enable', $result);
            $this->telnet->DoCommand(PHP_EOL, $result);

            $this->telnet->DoCommand('config', $result);
            $this->telnet->DoCommand(PHP_EOL, $result);

            /*
          ╔═════════════════════════════════════════════╗
          ║ Resetear la ont a fabrica para poder  ║
            aprovisionarla de neuvo si es necesario
          ╚══════════════════════════════════════════════╝
          */

            $this->telnet->DoCommand('interface gpon ' . $c . '/' . $t . PHP_EOL, $result);
            $this->telnet->DoCommand(PHP_EOL, $result);
            $this->util->log('interface gpon ' . $c . "/" . $t . " " . $result . $id_olt);
            //$telnet->DoCommand('clear' . PHP_EOL, $void);

            $this->telnet->DoCommand('ont factory-setting-restore ' . $p . ' ' . $idont . PHP_EOL . "y" . PHP_EOL, $result);


            $this->telnet->DoCommand('quit' . PHP_EOL . PHP_EOL, $void);

            /*
          ╔═════════════════════════════════════╗
          ║ Borra el servicio de internet  ║
          ╚═════════════════════════════════════╝
          */
            if (intval($idinternet)  > 0  && $internet==true) {
                $this->telnet->DoCommand('undo service-port ' . $idinternet . PHP_EOL, $result);

            }

            /*
          ╔═════════════════════════════════════╗
          ║ Borra el servicio de tv        ║
          ╚═════════════════════════════════════╝
          */
            if (intval($idtv) > 0 && $tv == true) {

                $this->telnet->DoCommand('undo service-port ' . $idtv . PHP_EOL, $result);
                $this->util->log('undo service-port ' . $idtv . " " . $result . " " . $id_olt);

            }

            /*
          ╔═════════════════════════════════════╗
          ║ Borra el servicio de Voz       ║
          ╚═════════════════════════════════════╝
          */
            if (intval($idvoz) > 0 && $voz == true) {

                $this->telnet->DoCommand('undo service-port ' . $idvoz . PHP_EOL, $result);
                $this->util->log('undo service-port ' . $idvoz . " " . $result . " " . $id_olt);

            }
            /*

            /*


//            /*
//           ╔═══════════════════════════════════════════════════════════════════╗
//           ║ Borra la ont del servidor acs para poder reutilizarla.  ║
//           ╚═══════════════════════════════════════════════════════════════════╝
//           */
//
//            $result = $this->util->selectWhere('acs_ids', array('id_acs'), " pon='" . $pon . "'");
//
//            while ($row = mysqli_fetch_array($result)) {
//                $id_device = $row[0];
//            }
//            if ($id_device == '')
//                $id_device = '00259E-HG8546M-' . $pon;
//
//            $url = "http://10.211.2.2:7557/devices/" . $id_device . "/";
//
//            $ch = curl_init();
//            $default_curl_options = array(
//                CURLOPT_SSL_VERIFYPEER => false,
//                CURLOPT_HEADER => true,
//                CURLOPT_RETURNTRANSFER => true,
//                CURLOPT_TIMEOUT => 10,
//            );
//
//            curl_setopt_array($ch, $default_curl_options);
//            curl_setopt($ch, CURLOPT_HEADER, true);
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
//

        }

    }

}








