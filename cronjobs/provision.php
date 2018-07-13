<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/05/2018
 * Time: 8:55
 */

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'http://10.211.2.2:7557/devices/');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
//print_r($obj);


foreach ($obj as $objeto) {
    $pon= $objeto->_id;
    echo $pon;
}

$url="http://10.211.2.2:7557/";
$ch = curl_init( $url );

$payload = json_encode(array('name'=>'getValues','parameterValues'=>[['device', 'xsd:string']]));
curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
# Return response instead of printing.
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
# Send request.
$result = curl_exec($ch);
echo "<pre>$result</pre>";


$url="http://10.211.2.2:7557/devices/00259E-HG8546M-48575443CDE3319A/tasks?connection_request";
$ch = curl_init( $url );
//
//# Setup request to send json via POST.
//$payload = json_encode(array('name'=>'setParameterValues','parameterValues'=>[['InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.X_HW_VLAN', '100', 'xsd:string'],['InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.2.X_HW_VLAN', '300', 'xsd:string']]));
//curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
//curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
//# Return response instead of printing.
//curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
//# Send request.
//$result = curl_exec($ch);
//echo "<pre>$result</pre>";
//
//$payload = json_encode( array( "name"=> 'setParameterValues', 'parameterValues'=>[['InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.Username', 'pruebapppoe', 'xsd:string']] ) );
//curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
//curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
//# Return response instead of printing.
//curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
//# Send request.
//$result = curl_exec($ch);
//echo "<pre>$result</pre>";
//
//
//$payload = json_encode( array( "name"=> 'setParameterValues', "parameterValues"=>[['InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.Password', 'clavepppoe' , 'xsd:string']] ) );
//curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
//curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
//# Return response instead of printing.
//curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
//# Send request.
//$result = curl_exec($ch);
//echo "<pre>$result</pre>";
//
//
//
//$payload = json_encode( array( "name"=> 'setParameterValues', "parameterValues"=>[['InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.NATEnabled', 'true', 'xsd:string']] ) );
//curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
//curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
//# Return response instead of printing.
//curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
//# Send request.
//$result = curl_exec($ch);
//echo "<pre>$result</pre>";

$payload = json_encode( array( "name"=> 'setParameterValues', "parameterValues"=>[['InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.Layer2Bridging.Filter.1.FilterEnable', '1', 'xsd:string'],['InternetGatewayDevice.Layer2Bridging.Filter.2.FilterEnable', '1', 'xsd:string'],['InternetGatewayDevice.Layer2Bridging.Filter.1.FilterEnable', '3', 'xsd:string'],['InternetGatewayDevice.Layer2Bridging.Filter.1.FilterEnable', '4', 'xsd:string']] ) );
curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
# Return response instead of printing.
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
# Send request.
$result = curl_exec($ch);
echo "<pre>$result</pre>";
//
//$payload = json_encode( array( "name"=> 'setParameterValues', "parameterValues"=>[["InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.SSID", "GenieACS", "xsd:string"]] ) );
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: PUT','Content-Type:application/json', 'Accept:application/json'));
//curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
//curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
//$result = curl_exec($ch);
//echo "<pre>$result</pre>";
//
//$payload = json_encode( array( "name"=> 'setParameterValues', "parameterValues"=>[["InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.PreSharedKey.1.PreSharedKey", "GenieACS", "xsd:string"]]) );
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: PUT','Content-Type:application/json', 'Accept:application/json'));
//curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
//curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
//$result = curl_exec($ch);
//echo "<pre>$result</pre>";



curl_close($ch);
# Print response.

?>
<!--<script type="text/javascript" src="../assets/plugins/jquery/jquery-2.2.3.min.js"></script>-->
<!---->
<!--<script>-->
<!---->
<!--    var url="http://5.40.80.171:7557/devices/00259E-HG8546M-48575443EB6B019A/tasks?connection_request";-->
<!---->
<!--    $.ajax({-->
<!--        url: url+encodeURIComponent(JSON.stringify({'name':'setParameterValues','parameterValues':[['InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.X_HW_VLAN', '100', 'xsd:string']]})),-->
<!--        type: "GET"-->
<!--    });-->
<!--</script>-->



<!---->