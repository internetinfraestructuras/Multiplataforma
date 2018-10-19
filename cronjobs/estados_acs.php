<?php

//*******************************************************************************************************
//  Interfaz que permite a los instaladores aprovisionar uno o varios servicios a una ont
//  tras seleccionar un cliente o crear uno nuevo se activan los servicios que quiere asignar
//  Internet, voz, Tv, se busca la ont conectada o se teclea su numero pon, se selecciona la velocidad
//  y se pulsa el boton aprovisionar
//*******************************************************************************************************

/*
    ╔═════════════════════════════════════════════════════════════════════════════════════════╗
    ║ REALIZA UNA COMPROBACION EN TIEMPO REAL SOBRE EL ESTADO DE UNA INSTALACION ║
    ║ CONSULTANDO DIRECTAMENTE EN EL SERVIDOR ACS, PODEMOS OBTENER EL ESTADO DE  ║
    ║ LAS 4 VIRTUAL LANS QUE SE HAN CONFIGURADO EN CADA ONT.                     ║
    ╚═════════════════════════════════════════════════════════════════════════════════════════╝
*/


/*  OBETENER LAS VELOCIDADES DISPONIBLES
    *
    * ruta:    ftth.internetinfraestructuras.es/api/verificar.php
    * espera:  id_orden: Identificador de la orden de la que se desea consultar el estado

        !!!!!  NOTA: la consulta se realiza sobre el dispositivo obteniendo la informacion real del momento de la consulta!!!!!!
        PUEDE TARDAR HASTA 30 SEGUNDOS, RECOMIENDO UNA BARRA ANIMADA DE ESPERA

    * devuelve:
    *          array:
            'internet' => true || false,
            'television' => true || false,
            'telefono' => true || false

            aunque la instalacion se haya realizado correctamente, pueden suceder errores externos como que el usuario de la voz
            no esté configurado correctamente en la plataforma de voz, lo mismo con la tv y con el acceso ppoe.

            'registro_vozip' => 'ERROR_REGISTRATION_AUTH_FAIL' || 'ERROR_NONE'  ||
    */


    require_once('../config/util.php');

    ini_set('display_errors', 0);
    error_reporting('E_ALL');
    $util = new util();


    $result= $util->selectWhere('aprovisionados',array('num_pon'));

    while ($row = mysqli_fetch_array($result)) {

        $pon = $row[0];
        echo $pon;

        $ch = curl_init('http://10.211.2.2:7557/devices/');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $json = json_decode($result, true);
        curl_close($ch);


        for ($c = 0; $c < count($json); $c++) {
            if ($json[$c]['_deviceId']['_SerialNumber'] == $pon) {
                $id_device = $json[$c]['_id'];
                break;
            }
        }


        $url = "http://10.211.2.2:7557/devices/" . $id_device . "/tasks?timeout=3000&connection_request";
echo $url;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $payload = json_encode(array('name' => 'refreshObject', 'objectName' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);

        $payload = json_encode(array('name' => 'refreshObject', 'objectName' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANIPConnection'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);

        $payload = json_encode(array('name' => 'refreshObject', 'objectName' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.2.WANIPConnection'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);

        $payload = json_encode(array('name' => 'refreshObject', 'objectName' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANIPConnection'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);

        $payload = json_encode(array('name' => 'refreshObject', 'objectName' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.4.WANIPConnection'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);

        $payload = json_encode(array('name' => 'refreshObject', 'objectName' => 'InternetGatewayDevice.Services.VoiceService.1.VoiceProfile.1.Line'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);

        // leemos los parametros desde el dispositivo
        $url = "http://10.211.2.2:7557/devices/?query=%7B%22_id%22%3A%22" . $id_device . "%22%7D";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $json = json_decode($result, true);

        $internet_pppoe = $json[0]['InternetGatewayDevice']['WANDevice'][1]['WANConnectionDevice'][2]['WANIPConnection'][1]['ConnectionStatus']['_value'];
        $n++;
        $internet_ip = $json[0]['InternetGatewayDevice']['WANDevice'][1]['WANConnectionDevice'][1]['WANPPPConnection'][1]['ConnectionStatus']['_value'];
        $n++;
        $vozip = $json[0]['InternetGatewayDevice']['WANDevice'][1]['WANConnectionDevice'][3]['WANIPConnection'][1]['ConnectionStatus']['_value'];
        $n++;
        $tv = $json[0]['InternetGatewayDevice']['WANDevice'][1]['WANConnectionDevice'][4]['WANIPConnection'][1]['ConnectionStatus']['_value'];
        $n++;
        $acceso_pppoe = $json[0]['InternetGatewayDevice']['WANDevice'][1]['WANConnectionDevice'][1]['WANPPPConnection'][1]['LastConnectionError']['_value'];
        $n++;
        $acceso_voz = $json[0]['InternetGatewayDevice']['Services']['VoiceService'][1]['VoiceProfile'][1]['Line'][1]['X_HW_LastRegisterError']['_value'];
        $n++;

        if ($internet_pppoe == 'Connected')
            $status_pppoe = true;
        else
            $status_pppoe = false;

        if ($internet_ip[0] == 'Connected')
            $status_dhcp = true;
        else
            $status_dhcp = false;


        if ($vozip == 'Connected')
            $status_tel = true;
        else
            $status_tel = false;

        if ($tv == 'Connected')
            $status_tv = true;
        else
            $status_tv = false;


        $campos = array('status_pppoe', 'status_dhcp', 'status_iptv', 'status_vozip', 'error_dhcp', 'error_vozip');
        $valores = array($status_pppoe, $status_dhcp, $status_tel, $status_tv, $acceso_pppoe, $acceso_voz);
        $util->update('estados_olt',$campos,$valores,"pon='".$pon."'");

        curl_close($ch);

    }


?>