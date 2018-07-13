<?php
/*
PHPTelnet 1.1.1
by Antone Roundy
adapted from code found on the PHP website
public domain
*/

include_once("config/util.php");

class PHPTelnet {
    var $show_connect_error=1;

    var $use_usleep=0;	// change to 1 for faster execution
    // don't change to 1 on Windows servers unless you have PHP 5
    var $sleeptime=125000;
    var $loginsleeptime=1000000;

    var $fp=NULL;
    var $loginprompt;

    var $conn1;
    var $conn2;

    /*
    0 = success
    1 = couldn't open network connection
    2 = unknown host
    3 = login failed
    4 = PHP version too low
    */
    function Connect($server,$user,$pass) {
        $rv=0;
        $vers=explode('.',PHP_VERSION);
        $needvers=array(4,3,0);
        $j=count($vers);
        $k=count($needvers);
        if ($k<$j) $j=$k;
        for ($i=0;$i<$j;$i++) {
            if (($vers[$i]+0)>$needvers[$i]) break;
            if (($vers[$i]+0)<$needvers[$i]) {
                $this->ConnectError(4);
                return 4;
            }
        }

        $this->Disconnect();

        if (strlen($server)) {
            if (preg_match('/[^0-9.]/',$server)) {
                $ip=gethostbyname($server);
                if ($ip==$server) {
                    $ip='';
                    $rv=2;
                }
            } else $ip=$server;
        } else $ip='127.0.0.1';

        if (strlen($ip)) {
            if ($this->fp=fsockopen($ip,23)) {
                fputs($this->fp,$this->conn1);
                $this->Sleep();

                fputs($this->fp,$this->conn2);
                $this->Sleep();
                $this->GetResponse($r);
                $r=explode("\n",$r);
                $this->loginprompt=$r[count($r)-1];

                fputs($this->fp,"$user\r");
                $this->Sleep();

                fputs($this->fp,"$pass\r");
                if ($this->use_usleep) usleep($this->loginsleeptime);
                else sleep(1);
                $this->GetResponse($r);
                $r=explode("\n",$r);
                if (($r[count($r)-1]=='')||($this->loginprompt==$r[count($r)-1])) {
                    $rv=3;
                    $this->Disconnect();
                }
            } else $rv=1;
        }

        if ($rv) $this->ConnectError($rv);
        return $rv;
    }

    function Disconnect($exit=1) {
        if ($this->fp) {
            if ($exit) $this->DoCommand('exit',$junk);
            fclose($this->fp);
            $this->fp=NULL;
        }
    }

    function DoCommand($c,&$r) {
        if ($this->fp) {
            fputs($this->fp,"$c\r");
            $this->Sleep();
            $this->GetResponse($r);
            $r=preg_replace("/^.*?\n(.*)\n[^\n]*$/","$1",$r);
        }
        return $this->fp?1:0;
    }

    function GetResponse(&$r) {
        $r='';
        do {
            $r.=fread($this->fp,1000);
            $s=socket_get_status($this->fp);
        } while ($s['unread_bytes']);
    }

    function Sleep() {
        if ($this->use_usleep) usleep($this->sleeptime);
        else sleep(1);
    }

    function PHPTelnet() {
        $this->conn1=chr(0xFF).chr(0xFB).chr(0x1F).chr(0xFF).chr(0xFB).
            chr(0x20).chr(0xFF).chr(0xFB).chr(0x18).chr(0xFF).chr(0xFB).
            chr(0x27).chr(0xFF).chr(0xFD).chr(0x01).chr(0xFF).chr(0xFB).
            chr(0x03).chr(0xFF).chr(0xFD).chr(0x03).chr(0xFF).chr(0xFC).
            chr(0x23).chr(0xFF).chr(0xFC).chr(0x24).chr(0xFF).chr(0xFA).
            chr(0x1F).chr(0x00).chr(0x50).chr(0x00).chr(0x18).chr(0xFF).
            chr(0xF0).chr(0xFF).chr(0xFA).chr(0x20).chr(0x00).chr(0x33).
            chr(0x38).chr(0x34).chr(0x30).chr(0x30).chr(0x2C).chr(0x33).
            chr(0x38).chr(0x34).chr(0x30).chr(0x30).chr(0xFF).chr(0xF0).
            chr(0xFF).chr(0xFA).chr(0x27).chr(0x00).chr(0xFF).chr(0xF0).
            chr(0xFF).chr(0xFA).chr(0x18).chr(0x00).chr(0x58).chr(0x54).
            chr(0x45).chr(0x52).chr(0x4D).chr(0xFF).chr(0xF0);
        $this->conn2=chr(0xFF).chr(0xFC).chr(0x01).chr(0xFF).chr(0xFC).
            chr(0x22).chr(0xFF).chr(0xFE).chr(0x05).chr(0xFF).chr(0xFC).chr(0x21);
    }

    function ConnectError($num) {
        if ($this->show_connect_error) switch ($num) {
            case 1: echo '<br />[PHP Telnet] <a href="http://www.geckotribe.com/php-telnet/errors/fsockopen.php">Connect failed: Unable to open network connection</a><br />'; break;
            case 2: echo '<br />[PHP Telnet] <a href="http://www.geckotribe.com/php-telnet/errors/unknown-host.php">Connect failed: Unknown host</a><br />'; break;
            case 3: echo '<br />[PHP Telnet] <a href="http://www.geckotribe.com/php-telnet/errors/login.php">Connect failed: Login failed</a><br />'; break;
            case 4: echo '<br />[PHP Telnet] <a href="http://www.geckotribe.com/php-telnet/errors/php-version.php">Connect failed: Your server\'s PHP version is too low for PHP Telnet</a><br />'; break;
        }
    }
}


$telnet = new PHPTelnet();
$util= new util();

// leo informacion de la olt seleccionada para obtener la ip, usuario, clave y demas.
$id_olt = $_POST['olt'];
$cabeceras = $util->selectWhere('olts', $t_cabeceras, ' id=' . $id_olt);
$row = mysqli_fetch_array($cabeceras);
$server = $row['ip'];
$user = $row['usuario'];
$pass = $row['clave'];

$c =  $_POST['c'];
$t =  $_POST['t'];
$p =  $_POST['p'];
$caja =  $_POST['caja'];
$puerto =  $_POST['puerto'];
$lat =  $_POST['lat'];
$lon =  $_POST['lon'];
$gpon=$c."/".$t."/".$p;


setcookie('c', $c, time() + (86400 * 30), "/");
setcookie('t', $t, time() + (86400 * 30), "/");
setcookie('p', $p, time() + (86400 * 30), "/");
setcookie('caja', $caja, time() + (86400 * 30), "/");
setcookie('puerto', $puerto, time() + (86400 * 30), "/");
setcookie('cabecera', $id_olt, time() + (86400 * 30), "/");


// if the first argument to Connect is blank,
// PHPTelnet will connect to the local host via 127.0.0.1


$command = $_POST['command'];
$serial = $_POST['serial'];

if($command=='alta'){
    $result = $util->selectWhere('aprovisionados',  array('id'), 'serial='.$serial);
    $row = mysqli_fetch_array($result);
    $r=intval($row[0]);

    if($r>0){
        echo "El número de serie de la ONT ya está registrado";
        return;
    }

}

$lineprofile = $_POST['lineprofile'];
$serverprofile = $_POST['serverprofile'];
$descripcion = str_replace(" ", "_",$_POST['descrp'])."_".$c."_".$t."_".$p."_".$caja."_".$puerto;
$descripcion=strtoupper($descripcion);

$lineprofile='11';

$idcliente = $_POST['idcliente'];
$servicio = $_POST['servicio'];
$up = $_POST['up'];
$dw = $_POST['dw'];

//datos del tipo de acceso
$gestionada = isset( $_POST['gestionada'])? $_POST['gestionada'] : "";
$tipoip = isset( $_POST['tipoip'])? $_POST['tipoip'] : "";
$asignada = isset( $_POST['asignada'])? $_POST['asignada'] : "";
$ppoe_usuario = isset( $_POST['usuario_ppoe'])? $_POST['usuario_ppoe'] : "";
$ppoe_passw = isset( $_POST['clave_ppoe'])? $_POST['clave_ppoe'] : "";

$gemport = substr($servicio,0,1);

$ont_id = intval($util->selectMax('control_id_ont',  'ont_id', "olt=". $id_olt." and c=".$c." and t=".$t." and p=".$p));
$id_internet = intval($util->selectMax('control_id_ont',  'id_datos', "olt=". $id_olt." and c=".$c." and t=".$t." and p=".$p));

//$id_internet = intval($util->selectLast('aprovisionados',  'id_internet', "cabecera=". $id_olt))+1;
//$id_voip = $util->selectLast('aprovisionados',  'id_voip', "cabecera=". $id_olt);
//$id_iptv = $util->selectLast('aprovisionados',  'id_iptv', "cabecera=". $id_olt);



//service-port 10 vlan 100 gpon 0/0/15 ont 10 gemport 1 multi-service user-vlan 100 tag-transform
// translate inbound traffic-table index 600 outbound traffic-table index 600

    $ont_id++;
    $id_internet++;


    $comando="ont add ".$p." ".$ont_id." sn-auth ".$serial." omci ont-lineprofile-id ".$lineprofile." ont-srvprofile-name ".$serverprofile." desc ".$descripcion;
    $comando1="service-port ".$id_internet." vlan ".$servicio." gpon ".$gpon." ont ".$ont_id." gemport ".$gemport.
        " multi-service user-vlan ".$servicio." tag-transform translate inbound traffic-table index ".$up." outbound traffic-table index ".$dw;

    $result = $telnet->Connect(str_replace(" ","",$server),str_replace(" ","",$user),str_replace(" ","",$pass));

    $respuesta1 = false;

    if ($result == 0) {
        $telnet->DoCommand('enable', $result);
        $telnet->DoCommand(PHP_EOL, $result);
        $telnet->DoCommand('config', $result);
        $telnet->DoCommand(PHP_EOL, $result);
        //echo 'interface gpon '.$c."/".$t;

        $telnet->DoCommand('interface gpon ' . $c . "/" . $t, $result);
        $telnet->DoCommand(PHP_EOL, $result);

        $telnet->DoCommand($comando, $result);
        $telnet->DoCommand(PHP_EOL, $result);

        $respuesta1 = strpos($result, 'success: 1');
        $ontduplicada = strpos($result, 'The ONT ID has already existed');

        if ($comando1 != "" && $respuesta1 == true) {
            $telnet->DoCommand("quit", $result);
            $telnet->DoCommand(PHP_EOL, $result);
            $telnet->DoCommand($comando1, $result);
            $telnet->DoCommand(PHP_EOL, $result);
            $respuesta2 = strpos($result, '[1');
        }

        $telnet->Disconnect();
    }


    if($respuesta1!==false && $respuesta2!==false) {

        $valores = array($idcliente, $_SESSION['USER_ID'], date('Y-m-d H:i:s'), $lat, $lon, '1', '0', '0', $serverprofile,
            $gestionada, $tipoip, $asignada, $descripcion, $up, $dw, $ppoe_usuario,
            $ppoe_passw, $ont_id, $c, $t, $p, $serial, $caja, $puerto, $id_internet, '0', '0', $id_olt);

        $lastid = $util->insertInto("aprovisionados", $t_aprovisionados, $valores);

        $util->insertInto("control_id_ont", array('olt','c','t','p','ont_id','id_datos','id_voz','id_tv'), array($id_olt, $c, $t,$p, $ont_id,$id_internet,'0','0'));
       echo 0;

    } else {
        echo $respuesta1;
        echo $respuesta2;
        echo -1;
    }

//-----------------------------------------------------------------------------
?>




