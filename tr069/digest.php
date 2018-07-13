<?php


$headers = array('Content-type: application/xml');
$url = 'http://192.168.100.1:7547';


error_reporting(E_ALL);
ini_set( 'display_errors','1');

$url = "http://10.206.1.253:7547";
$username = "demo";
$password = "demo";
$post_data = array(
    'fieldname1' => 'value1',
    'fieldname2' => 'value2'
);

$options = array(
    CURLOPT_URL            => $url,
    CURLOPT_HEADER         => true,
    CURLOPT_VERBOSE        => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,    // for https
    CURLOPT_USERPWD        => $username . ":" . $password,
    CURLOPT_HTTPAUTH       => CURLAUTH_DIGEST,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => http_build_query($post_data)
);

$ch = curl_init();

curl_setopt_array( $ch, $options );

try {
    $raw_response  = curl_exec( $ch );

    // validate CURL status
    if(curl_errno($ch))
        throw new Exception(curl_error($ch), 500);

    // validate HTTP status code (user/password credential issues)
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($status_code != 200)
        throw new Exception("Response with Status Code [" . $status_code . "].", 500);

} catch(Exception $ex) {
    if ($ch != null) curl_close($ch);
    throw new Exception($ex);
}

if ($ch != null) curl_close($ch);

echo "raw response: " . $raw_response;


//$username = 'demo';
//$password = 'demo';
//
/*$data = "<?xml version='1.0' encoding='UTF-8' standalone='yes'?><ns2:query xmlns:ns2='http://10.206.1.253:7547'><numberofItemsInPage>1</numberofItemsInPage><startPageNumber>1</startPageNumber></ns2:query>";*/
//
//$data = urlencode(utf8_decode(trim($data)));
//
//$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL, $url);
//curl_setopt($ch, CURLOPT_USERPWD, $username.':'.$password);
//curl_setopt($ch, CURLOPT_VERBOSE, 1);
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//curl_setopt($ch, CURLOPT_PUT, 1);
//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//curl_setopt($ch, CURLOPT_HEADER, true);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//$response = new stdClass;
//
//$response->data = curl_exec($ch);
//$response->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//
//curl_close($ch);
//
//var_dump($response);
?>