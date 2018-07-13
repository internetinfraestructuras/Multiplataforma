<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 20/04/2018
 * Time: 13:57
 */

//define ('DB_SERVER' , 'localhost');
//define ('DB_DATABASENAME', 'fibra');
//define ('DB_USER', 'root');
//define ('DB_PASSWORD', '');
//
//$link = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD)
//or die ("Error al conectar al servidor");
//mysqli_select_db($link, DB_DATABASENAME)
//or die ("Error al seleccionar base datos.");
//
//mysqli_query($link,"set names 'utf8'");




//$request = "";
//foreach (getallheaders() as $nombre => $valor) {
//    $request = $request. "$nombre: $valor\n";
//}


$username = "freeacs";
$password = "freeacs";

//class CurlRequest
//{
//    private $ch;
//    /**
//     * Init curl session
//     *
//     * $params = array('url' => '',
//     *                    'host' => '',
//     *                   'header' => '',
//     *                   'method' => '',
//     *                   'referer' => '',
//     *                   'cookie' => '',
//     *                   'post_fields' => '',
//     *                    ['login' => '',]
//     *                    ['password' => '',]
//     *                   'timeout' => 0
//     *                   );
//     */
//    public function init($params)
//    {
//        $this->ch = curl_init();
//        $user_agent = 'Mozilla/5.0 (Windows; U;
//Windows NT 5.1; ru; rv:1.8.0.9) Gecko/20061206 Firefox/1.5.0.9';
//        $header = array(
//            "Accept: text/xml,application/xml,application/xhtml+xml,
//text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5",
//            "Accept-Language: ru-ru,ru;q=0.7,en-us;q=0.5,en;q=0.3",
//            "Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7",
//            "Keep-Alive: 300");
//        if (isset($params['host']) && $params['host'])
//            $header[]="Host: ". $params['host'];
//        if (isset($params['header']) && $params['header'])
//            $header[]=$params['header'];
//
//        @curl_setopt ( $this -> ch , CURLOPT_RETURNTRANSFER , 1 );
//        @curl_setopt ( $this -> ch , CURLOPT_VERBOSE , 1 );
//        @curl_setopt ( $this -> ch , CURLOPT_HEADER , 1 );
//
//        if ($params['method'] == "HEAD") @curl_setopt($this -> ch,CURLOPT_NOBODY,1);
//        @curl_setopt ( $this -> ch, CURLOPT_FOLLOWLOCATION, 1);
//        @curl_setopt ( $this -> ch , CURLOPT_HTTPHEADER, $header );
//        if ($params['referer'])    @curl_setopt ($this -> ch , CURLOPT_REFERER, $params['referer'] );
//        @curl_setopt ( $this -> ch , CURLOPT_USERAGENT, $user_agent);
//        if ($params['cookie'])    @curl_setopt ($this -> ch , CURLOPT_COOKIE, $params['cookie']);
//
//        if ( $params['method'] == "POST" )
//        {
//            curl_setopt( $this -> ch, CURLOPT_POST, true );
//            curl_setopt( $this -> ch, CURLOPT_POSTFIELDS, $params['post_fields'] );
//        }
//        @curl_setopt( $this -> ch, CURLOPT_URL, $params['url']);
//        @curl_setopt ( $this -> ch , CURLOPT_SSL_VERIFYPEER, 0 );
//        @curl_setopt ( $this -> ch , CURLOPT_SSL_VERIFYHOST, 0 );
////        if (isset($params['login']) & isset($params['password']))
//
//        @curl_setopt($this -> ch , CURLOPT_USERNAME,'2c8143876a02f9b9264cce8c8f4bcabd');
//        @curl_setopt($this -> ch , CURLOPT_PASSWORD,'2c8143876a02f9b9264cce8c8f4bcabd');
//        @curl_setopt ( $this -> ch , CURLOPT_TIMEOUT, $params['timeout']);
//    }
//
//    /**
//     * Make curl request
//     *
//     * @return array  'header','body','curl_error','http_code','last_url'
//     */
//    public function exec()
//    {
//        $response = curl_exec($this->ch);
//        $error = curl_error($this->ch);
//        $result = array( 'header' => '',
//            'body' => '',
//            'curl_error' => '',
//            'http_code' => '',
//            'last_url' => '');
//        if ( $error != "" )
//        {
//            $result['curl_error'] = $error;
//            return $result;
//        }
//
//        $header_size = curl_getinfo($this->ch,CURLINFO_HEADER_SIZE);
//        $result['header'] = substr($response, 0, $header_size);
//        $result['body'] = substr( $response, $header_size );
//        $result['http_code'] = curl_getinfo($this -> ch,CURLINFO_HTTP_CODE);
//        $result['last_url'] = curl_getinfo($this -> ch,CURLINFO_EFFECTIVE_URL);
//        return $result;
//    }
//
//
//
//}
//
//try
//{
//    $params = array('url' => 'http://10.206.1.253:7547/2a2b7c42c542f9806c98bcb2be2bdf65',
//        'host' => 'http://10.206.1.253:7547/2a2b7c42c542f9806c98bcb2be2bdf65',
//        'header' => '',
//        'method' => 'GET', // 'POST','HEAD'
//        'referer' => '',
//        'cookie' => '',
//        'post_fields' => '',
//        'timeout' => 30
//    );
//
//    $o = new CurlRequest();
//    $o->init($params);
//    $result = $o->exec();
//    if ($result['curl_error'])    throw new Exception($result['curl_error']);
//    if ($result['http_code']!='200')    throw new Exception("HTTP Code = ".$result['http_code']);
//    if (!$result['body'])        throw new Exception("Body of file is empty");
//    echo $result;
//}
//catch (Exception $e)
//{
//    echo $e->getMessage();
//}


//
//$dominio = 'HuaweiHomeGateway';
//
//// usuario => contraseña
//$usuarios = array('admin' => 'freeacsfreeacs', 'invitado' => 'freeacsfreeacs');
//
//
//if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
//    header('HTTP/1.1 401 Unauthorized');
//    header('WWW-Authenticate: Digest realm="'.$dominio.
//        '",qop="auth",nonce="'.uniqid().'",opaque="'.md5($dominio).'"');
//
//    die('Texto a enviar si el usuario pulsa el botón Cancelar');
//}
//
//
//// Analizar la variable PHP_AUTH_DIGEST
//if (!($datos = analizar_http_digest($_SERVER['PHP_AUTH_DIGEST'])) ||
//    !isset($usuarios[$datos['username']]))
//    die('Credenciales incorrectas');
//
//
//// Generar una respuesta válida
//$A1 = md5($datos['username'] . ':' . $dominio . ':' . $usuarios[$datos['username']]);
//$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$datos['uri']);
//$respuesta_válida = md5($A1.':'.$datos['nonce'].':'.$datos['nc'].':'.$datos['cnonce'].':'.$datos['qop'].':'.$A2);
//
//if ($datos['response'] != $respuesta_válida)
//    die('Credenciales incorrectas');
//
//// Todo bien, usuario y contraseña válidos
//echo 'Se ha identificado como: ' . $datos['username'];
//
//
//// Función para analizar la cabecera de autenticación HTTP
//function analizar_http_digest($txt)
//{
//    // Protección contra datos ausentes
//    $partes_necesarias = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>'http://10.206.1.253:7547', 'response'=>1);
//    $datos = array();
//    $claves = implode('|', array_keys($partes_necesarias));
//
//    preg_match_all('@(' . $claves . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $coincidencias, PREG_SET_ORDER);
//
//    foreach ($coincidencias as $c) {
//        $datos[$c[1]] = $c[3] ? $c[3] : $c[4];
//        unset($partes_necesarias[$c[1]]);
//    }
//
//    return $partes_necesarias ? false : $datos;
//}

$url = 'http://10.206.1.253:7547';
$headers = array('Content-type: application/xml');
$username = 'freeacsfreeacs';
$password = 'freeacsfreeacs';

$data = "<?xml version='1.0' encoding='UTF-8' standalone='yes'?><ns2:query xmlns:ns2='http://10.206.1.253:7547'><numberofItemsInPage>1</numberofItemsInPage><startPageNumber>1</startPageNumber></ns2:query>";

$data = urlencode(utf8_decode(trim($data)));

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERPWD, $username.':'.$password);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_PUT, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$response->data = curl_exec($ch);
$response->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

var_dump($response);
?>