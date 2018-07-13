<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 26/03/2018
 * Time: 10:26
 */
//
//$a = " ___0_0_0_11.3_1
//  Failure: SN already exists
//
//MA5608T(config-if-gpon-0/0)#
//
//MA5608T(config-if-gpon-0/0)#
//
//MA5608T(config-if-gpon-0/0)#";
//
//if(strpos($a, 'SN already exis')>0) {
//    $responder = "Error: Numero de PON de ONT ya está aprovisionado";
//    $err_num = 1;
//    echo $responder;
//}

//$a=array('1','2','3');
//echo array_shift($a);
//print_r($a);
//echo array_shift($a);
//print_r($a);
//echo array_shift($a);
//print_r($a);

$a="


MA5608T(config-if-gpon-0/1)#display ont wan-info 13 0
    ---------------------------------------------------------------------
  F/S/P                      : 0/1/13
  ONT ID                     : 0
---------------------------------------------------------------------
  Index                      : 1
  Name                       : 1_INTERNET_R_VID_100
  Service type               : Internet
  Connection type            : IP routed
  IPv4 Connection status     : Connected
  IPv4 access type           : PPPoE
  IPv4 address               : 10.20.0.2
  Subnet mask                : 255.255.255.255
  Default gateway            : 10.255.255.255
  Manage VLAN                : 100
  Manage priority            : 0
  Option60                   : No
  Switch                     : Enable
  MAC address                : C4FF-1F9C-71F6
  Priority policy            : Specified
  L2 encap-type              : PPPoE
  IPv4 switch                : Enable
  IPv6 switch                : Disable
  Prefix                     : -

";

//$rows = explode("---------------------------------------------------------------------", $a);
//
//print "<pre>";
//var_dump($rows);
//print "</pre>";
//
//$rows = explode(PHP_EOL, $rows[2]);
//
//
//print "<pre>";
//var_dump($rows);
//print "</pre>";

$a="
MA5608T(config-if-gpon-0/1)#display ont wlan-info 13 0
  -----------------------------------------------------------------------------
  F/S/P                    : 0/1/13
  ONT ID                   : 0
  The total number of SSID : 1
  -----------------------------------------------------------------------------
  SSID Index               : 1
  SSID                     : SOM-FIBRA_JJVM
  Wireless Standard        : IEEE 802.11b/g/n
  Administrative state     : enable
  Operational state        : up
  Maximum associate number : 32
  Current associate number : 1
  -----------------------------------------------------------------------------

MA5608T(config-if-gpon-0/1)#
";
//
//$rows = explode("---------------------------------------------------------------------", $a);
//
//print "<pre>";
//var_dump($rows);
//print "</pre>";
//
//$rows = explode(PHP_EOL, $rows[2]);
//
//
//print "<pre>";
//var_dump($rows);
//print "</pre>";
//
//$i = explode(':', $rows[7]);
//print "<pre>";
//var_dump($i);
//print "</pre>";

/*
 * array(22) {
  [0]=>
  string(0) ""
  [1]=>
  string(32) "  Index                      : 1"
  [2]=>
  string(51) "  Name                       : 1_INTERNET_R_VID_100"
  [3]=>
  string(39) "  Service type               : Internet"
  [4]=>
  string(40) "  Connection type            : IP routed"
  [5]=>
  string(40) "  IPv4 Connection status     : Connected"
  [6]=>
  string(36) "  IPv4 access type           : PPPoE"
  [7]=>
  string(40) "  IPv4 address               : 10.20.0.2"
  [8]=>
  string(46) "  Subnet mask                : 255.255.255.255"
  [9]=>
  string(45) "  Default gateway            : 10.255.255.255"
  [10]=>
  string(34) "  Manage VLAN                : 100"
  [11]=>
  string(32) "  Manage priority            : 0"
  [12]=>
  string(33) "  Option60                   : No"
  [13]=>
  string(37) "  Switch                     : Enable"
  [14]=>
  string(45) "  MAC address                : C4FF-1F9C-71F6"
  [15]=>
  string(40) "  Priority policy            : Specified"
  [16]=>
  string(36) "  L2 encap-type              : PPPoE"
  [17]=>
  string(37) "  IPv4 switch                : Enable"
  [18]=>
  string(38) "  IPv6 switch                : Disable"
  [19]=>
  string(32) "  Prefix                     : -"
  [20]=>
  string(0) ""
  [21]=>
  string(0) ""
}

array(9) {
    [0]=>
  string(8) "--------"
    [1]=>
  string(30) "  SSID Index               : 1"
    [2]=>
  string(43) "  SSID                     : SOM-FIBRA_JJVM"
    [3]=>
  string(45) "  Wireless Standard        : IEEE 802.11b/g/n"
    [4]=>
  string(35) "  Administrative state     : enable"
    [5]=>
  string(31) "  Operational state        : up"
    [6]=>
  string(31) "  Maximum associate number : 32"
    [7]=>
  string(30) "  Current associate number : 1"
    [8]=>
  string(2) "  "
}

 */



//include_once("config/util.php");
//
//$util= new util();
//
//ini_set('max_execution_time', 300);
//ini_set('memory_limit', 1024*1024);
//
//
//$ont_ids = $util->selectWhere2('control_id_ont',  array('distinct(ont_id)'), "olt=4 and c=0 and t=0 and p=1",'ont_id');
//
//print_r($ont_ids);
//
//for($co=1;$co<=128;$co++){
//    if(!in_array($co,$ont_ids)){
//        $ont_id=$co;
//        break;
//    }
//}
//
//echo $ont_id;
//
//$a="
//MA5608T(config-if-gpon-0/0)#ont add 1 sn-auth \"5555554555555555\" omci ont-lineprofile-id 11 ont-srvprofile-name IPTVM12 desc \"1_1_0_0_0_1_1\"
//  Number of ONTs that can be added: 1, success: 1
//  PortID :1, ONTID :2
//
//MA5608T(config-if-gpon-0/0)#
//";
//
//$n = strpos($a,'ONTID :');
//echo $n;
//
//echo substr($a,$n+7,3);

$a="
MA5608T(config)#display ont load state all
 --------------------------------------------------------------
   F/S/P          ONT ID         Load state      Load progress
 --------------------------------------------------------------
   0/0/3               0                  -                  -
   0/0/4               6                  -                  -
   0/0/6               0                  -                  -
   0/0/8              30                  -                  -
   0/0/9               5                  -                  -
   0/0/12              5                  -                  -
   0/0/13              0                  -                  -
   0/0/14              0                  -                  -
   0/1/1               5                  -                  -
   0/1/7               0                  -                  -
   0/1/7               1                  -                  -
   0/1/7              67                  -                  -
   0/1/12              0                  -                  -
   0/1/12              1                  -                  -
 --------------------------------------------------------------";

//
//$rows = explode("--------------------------------------------------------------", $a);
//
//print "<pre>";
//var_dump($rows);
//print "</pre>";
//
//$rows = explode(PHP_EOL, $rows[2]);
//print "<pre>";
//var_dump($rows);
//print "</pre>";
////$row2 = explode("", $a);


$a="
MA5608T(config)#display ont info by-sn 4857544300FFDC9B
  -----------------------------------------------------------------------------
  F/S/P                   : 0/0/15
  ONT-ID                  : 0
  Control flag            : active
  Run state               : online
  Config state            : normal
  Match state             : match
  DBA type                : SR
  ONT distance(m)         : 1074
  ONT battery state       : holding state
  Memory occupation       : 84%
  CPU occupation          : 1%
  Temperature             : 55(C)
  Authentic type          : SN-auth
  SN                      : 4857544300FFDC9B (HWTC-00FFDC9B)
  Management mode         : OMCI
  Software work mode      : normal
  Isolation state         : normal
  ONT IP 0 address/mask   : -
  Description             : FRANCISCO_JAVIER_CHA_0_0_15_9.7_
                            1
  Last down cause         : LOS
  Last up time            : 2018-04-06 19:09:27+08:00


MA5608T(config)#
";

//$rows = explode("-----------------------------------------------------------------------------", $a);
//
//print "<pre>";
//var_dump($rows);
//print "</pre>";
//
//$rows = explode(PHP_EOL, $rows[1]);
//print "<pre>";
//var_dump($rows);
//print "</pre>";
//
//$ctp = explode(':', $rows[1]);
//echo $ctp[1];
//$ctp=explode('/', $ctp[1]);
//$c = $ctp[0];
//$t = $ctp[1];
//$p = $ctp[2];
//
//echo $c;
//echo $t;
//echo $p;
//
//$ctp = explode(':', $rows[2]);
//echo $ctp[1];


//display service-port port 0/0/12 ont 12

//$a="
//MA5608T(config)#display service-port port 0/0/12 ont 12
//{ <cr>|gemport<K>|sort-by<K> }:
//
//  Command:
//          display service-port port 0/0/12 ont 12
//  Switch-Oriented Flow List
//  -----------------------------------------------------------------------------
//   INDEX VLAN VLAN     PORT F/ S/ P VPI  VCI   FLOW  FLOW       RX   TX   STATE
//         ID   ATTR     TYPE                    TYPE  PARA
//  -----------------------------------------------------------------------------
//      22  100 common   gpon 0/0 /12 12   1     vlan  100        20   20   up
//  -----------------------------------------------------------------------------
//   Total : 1  (Up/Down :    1/0)
//
//MA5608T(config)#
//
//";
//
//
//$rows = explode("-----------------------------------------------------------------------------", $a);
//$rows = explode(PHP_EOL, $rows[2]);
//$rows = explode("  ", $rows[1]);
//$port = intval($rows[3]);
//echo $port;
/*
 * array(2) {
  [0]=>
  string(1) "0"
  [1]=>
  string(1) "0"
}
array(2) {
  [0]=>
  string(0) ""
  [1]=>
  string(2) "12"
}
array(1) {
  [0]=>
  string(2) "12"
}
 */

//$a="
//display ont info by-sn 48575443EB8E6B9B
//  -----------------------------------------------------------------------------
//  F/S/P                   : 0/0/7
//  ONT-ID                  : 2
//  Control flag            : active
//  Run state               : offline
//  Config state            : initial
//  Match state             : initial
//  DBA type                : -
//  ONT distance(m)         : -
//  ONT battery state       : -
//";
//
//$rows = explode("-----------------------------------------------------------------------------", $a);
//$rows = explode(PHP_EOL, $rows[1]);
//
//$ont_ids = explode(':', $rows[2]);
//print_r($ont_ids);
//$ont_id = intval(explode(':', $ont_ids[1]));
include_once("config/util.php");

require('clases/routeros_api.class.php');

$API = new RouterosAPI();

if ($API->connect('62.175.252.1', 'NexwrfApiPppoe', '2018apinex*')) {

    $ARRAY = $API->comm('/ppp/profile/getall');
//    print_r($ARRAY);
    foreach ($ARRAY as $clave) {
        echo $clave['name'];
    }

    $API->disconnect();

}