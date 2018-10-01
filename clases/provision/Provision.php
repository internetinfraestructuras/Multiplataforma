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


        $result = $this->util->selectWhere(FIBRADB."perfil_internet", $campos,
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
}








