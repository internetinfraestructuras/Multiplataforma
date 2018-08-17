<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */


/*
    ╔═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╗
    ║ Borra de la cabecera un service port y una ont                                                                   ║
    ║ Recibe como parametro post a: tiene que ser un string 'borrar_en_olt                                             ║
    ║ el id del registro a borrar y que se encuentra en la tabla aprovisionados, de ahi cogemos los datos de la        ║
    ║ cabecera y todo lo demas como pon, c, t, p, etc                                                                           ║
    ╚═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╝
*/


if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');
$util = new util();
check_session(1);

$telnet = new PHPTelnet();


if (isset($_POST['a']) && $_POST['a'] == 'borrar_en_olt') {

    if (isset($_POST['p']) && $_POST['p'] != '') {

        if (isset($_POST['hash']) && $_POST['hash'] == md5($_POST['p'])) {

            $r = $util->selectJoin('aprovisionados', array('id_en_olt', 'c', 't', 'p', 'id_internet', 'olts.ip', 'olts.usuario', 'olts.clave', 'aprovisionados.cabecera', 'id_voip', 'id_iptv', 'id_acs', 'num_pon'),
                ' JOIN olts ON olts.id = aprovisionados.cabecera ', '', "aprovisionados.id='" . $util->cleanstring($_POST['p']) . "';");

            $row = mysqli_fetch_array($r);

            $idont = $row['id_en_olt'];
            $c = $row['c'];
            $t = $row['t'];
            $p = $row['p'];
            $idinternet = $row['id_internet'];
            $idtv = $row['id_iptv'];
            $idvoz = $row['id_voip'];
            $idvpn = $row['id_vpn'];
            $idacs = $row['id_acs'];
            $server = $row['ip'];
            $user = $row['usuario'];
            $pass = $row['clave'];
            $pon = $row['num_pon'];
            $id_olt = $row[8];

            $result = $telnet->Connect($server, $user, $pass);

            if ($result == 0) {
                $telnet->DoCommand('enable', $result);
                $telnet->DoCommand(PHP_EOL, $result);

                $telnet->DoCommand('config', $result);
                $telnet->DoCommand(PHP_EOL, $result);

                /*
              ╔═══════════════════════════════════════════╗
              ║ Resetear la ont a fabrica para poder║
                aprovisionarla de neuvo si es necesario
              ╚═══════════════════════════════════════════╝
              */

                $telnet->DoCommand('interface gpon ' . $c . '/' . $t . PHP_EOL, $result);
                $telnet->DoCommand(PHP_EOL, $result);
                $util->log('interface gpon ' . $c . "/" . $t . " " . $result . $id_olt);
                $telnet->DoCommand('clear' . PHP_EOL, $void);

                $telnet->DoCommand('ont factory-setting-restore ' . $p . ' ' . $idont , $result);
                $telnet->DoCommand("y" . PHP_EOL, $result);
                $util->log("ont factory-setting-restore " . $p . " " . $idont . " " . $result . " " . $id_olt);

                $telnet->DoCommand('quit' . PHP_EOL, $void);
                $telnet->DoCommand('clear' . PHP_EOL, $void);

                /*
              ╔═════════════════════════════════════╗
              ║ Borra el servicio de internet  ║
              ╚═════════════════════════════════════╝
              */
                if (intval($idinternet) > 0) {
                    $telnet->DoCommand('undo service-port ' . $idinternet . PHP_EOL, $result);
                    $util->log('undo service-port ' . $idinternet . " " . $result . " " . $id_olt);
                    $telnet->DoCommand('clear' . PHP_EOL, $void);

                }

                /*
              ╔═════════════════════════════════════╗
              ║ Borra el servicio de tv        ║
              ╚═════════════════════════════════════╝
              */
                if (intval($idtv) > 0) {

                    $telnet->DoCommand('undo service-port ' . $idtv . PHP_EOL, $result);
                    $util->log('undo service-port ' . $idtv . " " . $result . " " . $id_olt);
                    $telnet->DoCommand('clear' . PHP_EOL, $void);

                }

                /*
              ╔═════════════════════════════════════╗
              ║ Borra el servicio de Voz       ║
              ╚═════════════════════════════════════╝
              */
                if (intval($idvoz) > 0) {

                    $telnet->DoCommand('undo service-port ' . $idvoz . PHP_EOL, $result);
                    $util->log('undo service-port ' . $idvoz . " " . $result . " " . $id_olt);
                    $telnet->DoCommand('clear' . PHP_EOL, $void);

                }
                /*
              ╔═════════════════════════════════════╗
              ║ Borra el servicio de VPn       ║
              ╚═════════════════════════════════════╝
              */
                if (intval($idvpn) > 0) {

                    $telnet->DoCommand('undo service-port ' . $idvpn . PHP_EOL, $result);
                    $util->log('undo service-port ' . $idvpn . " " . $result . " " . $id_olt);
                    $telnet->DoCommand('clear' . PHP_EOL, $void);

                }

                /*
              ╔═════════════════════════════════════╗
              ║ Borra el servicio de ACS       ║
              ╚═════════════════════════════════════╝
              */
                if (intval($idacs) > 0) {

                    $telnet->DoCommand('undo service-port ' . $idacs . PHP_EOL, $result);
                    $util->log('undo service-port ' . $idacs . " " . $result . " " . $id_olt);
                    $telnet->DoCommand('clear' . PHP_EOL, $void);

                }

                /*
              ╔═══════════════════════════════════════════╗
              ║ Cambia la interface y Borra la ont  ║
              ╚═══════════════════════════════════════════╝
              */

                $telnet->DoCommand('interface gpon ' . $c . '/' . $t . PHP_EOL. PHP_EOL, $result);
//                $telnet->DoCommand(PHP_EOL, $result);
                $util->log('interface gpon ' . $c . "/" . $t . " " . $result . $id_olt);
                $telnet->DoCommand('clear' . PHP_EOL, $void);

                $telnet->DoCommand('ont delete ' . $p . ' ' . $idont, $result);
                $telnet->DoCommand("y" . PHP_EOL, $result);
                $util->log("ont delete " . $p . " " . $idont . " " . $result . " " . $id_olt);

                /*
               ╔═══════════════════════════════════════════════════════════════════╗
               ║ Borra la ont del servidor acs para poder reutilizarla.  ║
               ╚═══════════════════════════════════════════════════════════════════╝
               */

                $result = $util->selectWhere('acs_ids', array('id_acs'), " pon='" . $pon . "'");

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
                $util->log(curl_exec($ch));


                /*
               ╔═══════════════════════════════════════════════════════╗
               ║Borra el registro de la tabla aprovisionados   ║
               ╚═══════════════════════════════════════════════════════╝
               */
                $q = "DELETE FROM aprovisionados WHERE id='" . $_POST['p'] . "';";
                $util->consulta($q);
                $telnet->Disconnect();
                $util->log($q);

            }


        } else echo "no serial";

    } else echo "no pon";

} else if (isset($_POST['a']) && $_POST['a'] == 'borrar_solo_alta') {

    if (isset($_POST['p']) && $_POST['p'] != '') {

        if (isset($_POST['hash']) && $_POST['hash'] == md5($_POST['p'])) {

            $q = "DELETE FROM aprovisionados WHERE id='" . $_POST['p'] . "';";
            $util->consulta($q);
            $telnet->Disconnect();
        } else echo "no serial";

    } else echo "no pon";

} else echo "no control";
