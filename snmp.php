<?php

if (!isset($_SESSION)) {
    @session_start();
}

require_once('config/util.php');
require_once('php/snmp/snmp.php');
include_once("php/ssh2.php");

ini_set('display_errors', 0);
error_reporting('E_ALL');
$util = new util();

?>
<!doctype html>
<html lang="en-US">

<body>


<!-- WRAPPER -->
<div id="wrapper">
    <form action="snmp.php" enctype="multipart/form-data" method="post">
        <input type="text" name="snmp" class="form-control">
        <input type="text" name="ssh" class="form-control">
        <br>
        <input type="submit" class="btn btn-success">

    </form>
    <?php
    /**
     * Created by PhpStorm.
     * User: Ruben
     * Date: 27/02/2018
     * Time: 10:03
     */



    set_time_limit(1500);
    $auth_pass='private';
    $priv_pass='private';
    ini_set('memory_limit', '256M');
    $ip = '10.201.112.2'; 		// ip address or hostname
    $community = 'private';		// community string

    $oid = $_REQUEST['snmp'];
    $sshcomand = $_REQUEST['ssh'];

    if($oid!='') {
        $snmp = new snmp();
        $snmp->version = SNMP_VERSION_2;
        $private = 'private';
        echo "<h2>" . $oid . "</h2>";
        echo $devuelto = $snmp->walk($ip, $oid, ['community' => $private]);
        foreach ($devuelto as $dato => $valor) {
            if ($valor !== "")
                echo $dato . "----" . $valor . "<br>";
        }
        echo "Fin";
        //echo $snmp->set($ip,'.1.3.6.1.4.1.2011.6.128.1.1.2.43.1.17.4194307840.1', 'NUEVO', 'x',['community' => $private]);

//    .1.3.6.1.4.1.2011.2.248
        function hexToStr($hex)
        {
            $string = '';
            for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
                $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
            }
            return $string;
        }

        function strToHex($string)
        {
            $hex = '';
            for ($i = 0; $i < strlen($string); $i++) {
                $hex .= dechex(ord($string[$i]));
            }
            return $hex;
        }
    }

    if($sshcomand!='') {
        set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');

        include_once('Net/SSH2.php');

        ini_set('display_errors', 0);
        error_reporting('E_ALL');

        $ssh = new Net_SSH2('10.201.112.2');
        if (!$ssh->login('puertosur', '1ppserrano*')) {
            exit('Login Failed');
        }

        $c=0;

        function packet_handler($str)
        {
            echo $str;
        }

        echo $ssh->read($sshcomand);
        echo $ssh->getLastError();

    }


    ?>
</div>
</section>
<!-- /MIDDLE -->

</div>

<!-- JAVASCRIPT FILES -->
<script type="text/javascript">var plugin_path = 'assets/plugins/';</script>
<script type="text/javascript" src="assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="assets/js/app.js"></script>

<script>

</script>
</body>
</html>