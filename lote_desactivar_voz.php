<?php

/**
 * Ese fichero recorre todos los dispositivos registrados en el acs pero que aparecen
 * en la tabla acs_ids, refresca la cadena que indica el ultimo error registrado en el registro de voz ip
 * y si es uno de los tres errores posible es porque no tiene la voz activada, en ese caso
 * seteamos el usuario de voz ip a disabled para que no siga realizando intentos de logeo sin exito
 */


//InternetGatewayDevice.Services.VoiceService.1.VoiceProfile.1.Line.1.X_HW_LastRegisterError
// ERROR_REGISTRATION_AUTH_FAIL

//InternetGatewayDevice.Services.VoiceService.1.VoiceProfile.1.Line.1.Enable
// Enabled


require_once('config/util.php');


ini_set('display_errors', 1);
error_reporting('E_ALL');
$util = new util();
check_session(3);


$result = $util->selectWhere('acs_ids', array('id_acs'));

$c=1;
while ($row1 = mysqli_fetch_array($result)) {
    $id_device = $row1[0];
//    echo $c. " " . $id_device ."<br>";
    $c++;
    ini_set('max_execution_time', 6000);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $url = "http://10.211.2.2:7557/devices/" . $id_device . "/tasks?timeout=3000&connection_request";
    $ch = curl_init($url);
    $payload = json_encode(array('name' => 'refreshObject', 'objectName' => 'InternetGatewayDevice.Services.VoiceService.1.VoiceProfile.1.Line.1.X_HW_LastRegisterError'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);

    $url = "http://10.211.2.2:7557/devices/?query=%7B%22_id%22%3A%22" . $id_device . "%22%7D";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result2 = curl_exec($ch);
    $json = json_decode($result2, true);
    $a=$json[0]['InternetGatewayDevice']['Services']['VoiceService'][1]['VoiceProfile'][1]['Line'][1]['X_HW_LastRegisterError']['_value'];
    if(trim($a)=='ERROR_REGISTRATION_AUTH_FAIL' || trim($a)=='ERROR_WAN_IP_NOT_OBTAINED' || trim($a)=='ERROR_REGISTRATION_TIME_OUT'){

        $url = "http://10.211.2.2:7557/devices/" . $id_device . "/tasks?connection_request";
        echo $url;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $payload = json_encode(array('name' => 'setParameterValues', 'parameterValues' => [['InternetGatewayDevice.Services.VoiceService.1.VoiceProfile.1.Line.1.Enable',
            'Disabled', 'xsd:string']]));

        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_exec($ch);


    }
    curl_close($ch);
}

//      ERROR_REGISTRATION_AUTH_FAIL
//      ERROR_WAN_IP_NOT_OBTAINED
//      ERROR_REGISTRATION_TIME_OUT
//