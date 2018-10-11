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
$util = new util();

date_default_timezone_set('Etc/UTC');

error_reporting(E_ALL);
ini_set("display_errors", 0);

ini_set('max_execution_time', 1600);
ini_set('memory_limit', 1024 * 1024);



$telnet = new PHPTelnet();
$ip=$_REQUEST['ip'];
$gateway=$_REQUEST['gateway'];
$user=$_REQUEST['user'];
$pass=$_REQUEST['pass'];

$r = $telnet->Connect($ip, $user, $pass);
echo $telnet->Connect("10.201.173.2", "root", "admin123");

$telnet->DoCommand('enable', $result);
$telnet->DoCommand(PHP_EOL, $result);
$telnet->DoCommand('config', $result);
$telnet->DoCommand(PHP_EOL, $result);

/*
$telnet->DoCommand("board confirm 0".PHP_EOL.PHP_EOL, $respuesta_olt);
$telnet->DoCommand("interface meth 0".PHP_EOL.PHP_EOL, $respuesta_olt);
$telnet->DoCommand("ip address ".$ip." 24".PHP_EOL.PHP_EOL, $respuesta_olt);
$telnet->DoCommand("ip route-static 0.0.0.0 0 ".$gateway.PHP_EOL.PHP_EOL, $respuesta_olt);
$telnet->DoCommand("terminal user name".$user.PHP_EOL.PHP_EOL, $respuesta_olt);
*/
$telnet->DoCommand("ip route-static 0.0.0.0 0 ".$gateway." permanent".PHP_EOL.PHP_EOL, $respuesta_olt);

$telnet->DoCommand("VLAN 100 smart".PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('vlan desc 100 description "INTERNET"'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand("port vlan 100 0/2 0".PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);

$telnet->DoCommand("VLAN 200 smart".PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand("port vlan 200 0/2 0".PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('vlan desc 200 description "OMCI"'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);

$telnet->DoCommand("VLAN 300 smart".PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand("port vlan 300 0/2 0".PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('vlan desc 300 description "VOIP"'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);

$telnet->DoCommand("VLAN 400 smart".PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand("port vlan 400 0/2 0".PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('vlan desc 400 description "IPTV"'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);

$telnet->DoCommand("VLAN 500 smart".PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand("port vlan 500 0/2 0".PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('vlan desc 500 description "VPN"'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);

$telnet->DoCommand('dba-profile add profile-id 10 profile-name "UP_DBA" type4 max 600960'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('dba-profile add profile-id 30 profile-name "VOIP" type3 assure 30976 max 100992'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('dba-profile add profile-id 40 profile-name "OMCI_DBA" type2 assure 5056'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('dba-profile add profile-id 50 profile-name "IPTV" type4 max 204800'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('dba-profile add profile-id 60 profile-name "VPN" type4 max 600960'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);

$telnet->DoCommand('ont-lineprofile gpon profile-id 11 profile-name "generic_line-profile"'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('fec-upstream enable'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('tr069-management enable'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('tr069-management ip-index 0'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('tcont 0 dba-profile-id 40'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('tcont 1 dba-profile-id 10'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('tcont 2 dba-profile-id 30'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('tcont 3 dba-profile-id 30'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('tcont 4 dba-profile-id 40'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('tcont 5 dba-profile-id 60'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);

$telnet->DoCommand('gem add 1 eth tcont 1 encrypt on'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('gem add 2 eth tcont 2 encrypt on'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('gem add 3 eth tcont 3 encrypt on'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('gem add 4 eth tcont 4 encrypt on'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('gem add 5 eth tcont 5 encrypt on'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);

$telnet->DoCommand('gem mapping 1 1 vlan 100'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('gem mapping 2 2 vlan 200'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('gem mapping 3 3 vlan 300'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('gem mapping 4 4 vlan 400'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('gem mapping 5 5 vlan 500'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);

$telnet->DoCommand('commit'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('quit'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
sleep(10);


$telnet->DoCommand('ont-srvprofile gpon profile-id 11 profile-name "IPTV12M"'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('ont-port pots 2 eth 4'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('commit'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('quit'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
sleep(10);

$telnet->DoCommand('interface gpon 0/0'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('port 0 ont-auto-find enable'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('port 0 fec enable'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('port 1 ont-auto-find enable'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('port 1 fec enable'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('port 2 ont-auto-find enable'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('port 2 fec enable'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('port 3 ont-auto-find enable'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('port 3 fec enable'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('port 4 ont-auto-find enable'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('port 4 fec enable'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('port 5 ont-auto-find enable'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('port 5 fec enable'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('port 6 ont-auto-find enable'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('port 6 fec enable'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('port 7 ont-auto-find enable'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('port 7 fec enable'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
//if($pons=='16') {
    $telnet->DoCommand('port 8 ont-auto-find enable' . PHP_EOL . PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
    $telnet->DoCommand('port 8 fec enable' . PHP_EOL . PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
    $telnet->DoCommand('port 9 ont-auto-find enable' . PHP_EOL . PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
    $telnet->DoCommand('port 9 fec enable' . PHP_EOL . PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
    $telnet->DoCommand('port 10 ont-auto-find enable' . PHP_EOL . PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
    $telnet->DoCommand('port 10 fec enable' . PHP_EOL . PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
    $telnet->DoCommand('port 11 ont-auto-find enable' . PHP_EOL . PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
    $telnet->DoCommand('port 11 fec enable' . PHP_EOL . PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
    $telnet->DoCommand('port 12 ont-auto-find enable' . PHP_EOL . PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
    $telnet->DoCommand('port 12 fec enable' . PHP_EOL . PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
    $telnet->DoCommand('port 13 ont-auto-find enable' . PHP_EOL . PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
    $telnet->DoCommand('port 13 fec enable' . PHP_EOL . PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
    $telnet->DoCommand('port 14 ont-auto-find enable' . PHP_EOL . PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
    $telnet->DoCommand('port 14 fec enable' . PHP_EOL . PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
    $telnet->DoCommand('port 15 ont-auto-find enable' . PHP_EOL . PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
    $telnet->DoCommand('port 15 fec enable' . PHP_EOL . PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
//}

$telnet->DoCommand('quit'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
sleep(10);

$telnet->DoCommand('traffic table ip index 9 name 9 cir off priority 3 priority-policy tag-in-package'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('traffic table ip index 10 name 10 cir 10240 cbs 32968 pir 10240 pbs 32968 priority 0 priority-policy tag-in-package'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('traffic table ip index 30 name 30 cir 30336 cbs 972752 pir 30336 pbs 972752 priority 0 priority-policy tag-in-package'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('traffic table ip index 40 name 40 cir 40384 cbs 1294288 pir 40384 pbs  1294288 priority 0 priority-policy tag-in-package'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('traffic table ip index 50 name 50 cir 50496 cbs 1617872 pir 50496 pbs  1617872 priority 0 priority-policy tag-in-package'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('traffic table ip index 60 name 60 cir 60608 cbs 1941456 pir 60608 pbs  1941456 priority 0 priority-policy tag-in-package'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('traffic table ip index 70 name 70 cir 70720 cbs 2265040 pir 70720 pbs  2265040 priority 0 priority-policy tag-in-package'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('traffic table ip index 80 name 80 cir 80832 cbs 2588624 pir 80832 pbs  2588624 priority 0 priority-policy tag-in-package'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('traffic table ip index 90 name 90 cir 90880 cbs 2910160 pir 90880 pbs  2910160 priority 0 priority-policy tag-in-package'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('traffic table ip index 100 name 100 cir 100928 cbs 3231696 pir 100928  pbs 3231696 priority 0 priority-policy tag-in-package'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('traffic table ip index 150 name 150 cir 150000 cbs 3231696 pir 150000  pbs 3231696 priority 0 priority-policy tag-in-package'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('traffic table ip index 300 name 300 cir 329984 cbs 10240000 pir 329984 pbs 10240000 priority 0 priority-policy tag-in-package'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('traffic table ip index 600 name 600 cir 600000 cbs 10240000 pir 600000 pbs 10240000 priority 0 priority-policy tag-in-package'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('traffic table ip index 1000 name 1000 cir 1000000 cbs 10240000 pir 1000000 pbs 10240000 priority 0 priority-policy tag-in-package'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);

$telnet->DoCommand('save'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
sleep(80);

$telnet->DoCommand('ont-sipagent-profile add profile-id 2 proxy-server 89.140.16.250 outbound-server 89.140.16.250 home-domain t1.voipreq.com outbound-server-port 5060 proxy-server-port 5060 secondary-proxy-server-port 5060'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('ont-digitmap-profile token add profile-id 1 dial-plan-token [xABCD].S|[xABCD].#'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('ont-lineprofile gpon profile-id 11'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('tr069-management enable'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('commit'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
$telnet->DoCommand('quit'.PHP_EOL.PHP_EOL, $respuesta_olt);echo $respuesta_olt."<br>";sleep(2);
?>


