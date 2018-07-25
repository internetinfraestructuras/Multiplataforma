<?php
/**
 * Created by PhpStorm.
 * User: rcc
 * Date: 7/12/17
 * Time: 16:39
 */


if (!isset($_SESSION)) {@session_start();}

require_once('define.php');
require_once('def_tablas.php');

date_default_timezone_set('Europe/Madrid');


class util {

    public function sanear_string($string)
    {

        $string = trim($string);

        $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $string
        );

        $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $string
        );

        $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $string
        );

        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $string
        );

        $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $string
        );

        $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C',),
            $string
        );


    return $string;
}
    public function conectar(){
        $link = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD,DB_DATABASENAME);

        if (mysqli_connect_errno()) {
           printf("Falló la conexión: %s\n", mysqli_connect_error());
           exit();
       }
//        mysqli_select_db($link, DB_DATABASENAME)
//        or die ("Error al seleccionar base datos.");

        $link->query("set names 'utf8'");

        return $link;


        /* verificar la conexión */



    }
    public function selectSome($tabla, $campos, $order){
        $link = $this->conectar();
        
        $columnas = limpiar(implode($campos, ", "));

        $query = 'SELECT '.$columnas.' FROM ' . $tabla ;
        $query .=  $order!='' ? ' ORDER BY '.$order : "";

        if (!($result = $link->query($query)))
            throw new Exception('Error en selectAll.');

        $numFields = mysqli_num_fields($result);
        for ($i = 0; $i < $numFields; $i++) {
            $fieldNames[] = mysqli_fetch_field_direct($result, $i);
        }

       $link->close();

        return $fieldNames;
    }


    public function selectAll($tabla, $campos='', $order=null){
        try {
            $link = $this->conectar();

            $query = 'SELECT * FROM ' . $tabla;
            if ($order != '')
                $query = $query . ' ORDER BY ' . $order;

            if (!($result = $link->query($query)))
                throw new Exception('Error en selectAll.');

            $numFields = mysqli_num_fields($result);
            for ($i = 0; $i < $numFields; $i++) {
                $fieldNames[] = mysqli_fetch_field_direct($result, $i);
            }

            $link->close();
        } catch (Exception $e) {
            $this->log('Error selectAll: ' . $query);
        }

        return $fieldNames;
    }

    public function selectAllWhere($tabla, $where='', $order=null){
        try {
            $link = $this->conectar();

            $query = 'SELECT * FROM ' . $tabla;
            if ($order != '')
                $query = $query . $where . ' ORDER BY ' . $order;

            if (!($result = $link->query($query)))
                throw new Exception('Error en selectAll.');

            $numFields = mysqli_num_fields($result);
            for ($i = 0; $i < $numFields; $i++) {
                $fieldNames[] = mysqli_fetch_field_direct($result, $i);
            }

            $link->close();
        } catch (Exception $e) {
            $this->log('Error selectAllWhere: ' . $query);
        }

        return $fieldNames;
    }

    public function selectJoin($tabla, $campos, $join, $order, $where=null, $group=null){
        try {

            $link = $this->conectar();
            $columnas = limpiar(implode($campos, ", "));

            $query = 'SELECT '. $columnas. ' FROM ' . $tabla;

            if($join != null)
                $query = $query  . " ". $join;

            if($where != null)
                $query = $query  . " WHERE " . $where;

            if ($group != null)
                $query = $query . " GROUP BY ".$group ;

            if ($order != null)
                $query = $query . " ORDER BY ".$order ;
//            echo $query;
            if (!($result = $link->query($query)))
                throw new Exception('Error en selectJoin.');

            $link->close();

            return $result;

        } catch (Exception $e) {
            $this->log('Error selectJoin: ' . $query);
        }


    }


    public function consulta($query){
        try {

            $link = $this->conectar();

            if (!($result = $link->query($query)))
                throw new Exception();

            $fieldNames=array();

            $numFields = mysqli_num_fields($result);
            for ($i = 0; $i < $numFields; $i++) {
                $fieldNames[] = mysqli_fetch_field_direct($result, $i);
            }

            $link->close();

            return $fieldNames;
        } catch (Exception $e) {
            $this->log('consulta: ' . $query);
        }
        return $fieldNames;

    }

    public function selectWhere($tabla, $campos, $where=null, $order=null, $group=null){


        try {

            $link = $this->conectar();
            $columnas = implode($campos, ",");

            $query = 'SELECT '. $columnas . ' FROM ' . $tabla;

            if($where != null)
                $query = $query  . " WHERE " . $where;

            if($group != null)
                $query = $query . " GROUP BY ".$group ;

            if ($order != null)
                $query = $query . " ORDER BY ".$order ;
//echo $query;
            if (!($result = $link->query($query)))
                throw new Exception();
//            $this->log($query);
           $link->close();

            return $result;
        } catch (Exception $e) {
            $this->log('Error Select: ' . $query);
        }
        return $result;

    }

    public function selectWhere2($tabla, $campos, $where=null, $order=null, $group=null){


        try {

            $link = $this->conectar();
            $columnas = implode($campos, ",");

            $query = 'SELECT '. $columnas . ' FROM ' . $tabla;

            if($where != null)
                $query = $query  . " WHERE " . $where;

            if($group != null)
                $query = $query . " GROUP BY ".$group ;

            if ($order != null)
                $query = $query . " ORDER BY ".$order ;
           // //echo $query;
            if (!($result = $link->query($query)))
                throw new Exception();

            $fieldNames=array();

            while ($row = mysqli_fetch_array($result))
            {
                array_push($fieldNames, $row[0]);
            }
           // var_dump($fieldNames);
            $link->close();

            return $fieldNames;

        } catch (Exception $e) {
            $this->log('Error SelectWhere 2: ' . $query);

        }
        return $fieldNames;

    }

    public function selectWhere3($tabla, $campos, $where=null, $order=null, $group=null){


        try {

            $link = $this->conectar();

            $columnas = implode($campos, ",");

            $query = 'SELECT '. $columnas . ' FROM ' . $tabla;

            if($where != null)
                $query = $query  . " WHERE " . $where;

            if($group != null)
                $query = $query . " GROUP BY ".$group ;

            if ($order != null)
                $query = $query . " ORDER BY ".$order ;

             ////echo $query;

            if (!($result = $link->query($query)))
                throw new Exception();

            $fieldNames=array();

            while ($row = mysqli_fetch_array($result))
            {
                array_push($fieldNames, $row);
            }
            // var_dump($fieldNames);
            $link->close();

            return $fieldNames;

        } catch (Exception $e) {
            $this->log('Error SelectWhere 3: ' . $query);

        }
        return $fieldNames;

    }


    public function selectDistinct($tabla, $campo, $where=null){

        try {

            $link = $this->conectar();

            $query = 'SELECT DISTINCT '. $campo . ' FROM ' . $tabla;

            if($where != null)
                $query = $query  . " WHERE " . $where;

            if (!($result = $link->query($query)))
                throw new Exception('Error en selectWhere.');

            $fieldNames=array();

            while ($row = mysqli_fetch_array($result)){
                array_push($fieldNames, $row[0]);
            }


           $link->close();

            return $fieldNames;

        } catch (Exception $e) {
            $this->log('Excepción capturada: ' . $e->getMessage());
        }

    }

    public function selectMax($tabla, $campo, $where){

        $link = $this->conectar();

        $query = 'SELECT max('. $campo . ') FROM ' . $tabla;

        if($where!='')
            $query.= " where " . $where;
////echo $query;
        if (!($result = $link->query($query)))
            throw new Exception('Error en selectMax.');

        $row = mysqli_fetch_array($result);

       $link->close();

        return $row[0];

    }


    public function selectLast($tabla, $campo, $where){

        $link = $this->conectar();

        $query = 'SELECT '. $campo . ' FROM ' . $tabla;

        if($where!='')
            $query.= " where " . $where;

        $query.= " order by id desc limit 1 ";

        if (!($result = $link->query($query)))
            throw new Exception('Error en selectWhere.');

        $row = mysqli_fetch_array($result);

       $link->close();

        return $row[0];

    }


    public function insertInto($tabla, $campos, $valor, $log=true){

        try {


            $link = $this->conectar();

            $columnas = limpiar(implode($campos, ", "));

            $aItems = array();

            $valores = implode($valor, "', '");

            $query="INSERT INTO ".$tabla." (".$columnas.") VALUES ('".$valores."')";

//            echo $query;
            $query = str_replace("º","",$query);
            if (!($result = $link->query($query)))
                throw new Exception('Error en selectWhere.');

            $lastid = mysqli_insert_id($link);

           $link->close();
            if($log){
                $consulta= str_replace("'"," ",$query);
                $consulta.= str_replace(","," ",$query);
                $consulta.= "','".$lastid . "')";
                $this->loginsert($this->cleanstring($consulta),$lastid);
            }



            return $lastid;

        } catch (Exception $e) {
            $this->log('Error Insert: ' . $query);
        }
    }


    public function update($tabla, $campos, $valor, $where, $log=true){

        if($where=='')
            return;

        $link = $this->conectar();

        $columnas = limpiar(implode($campos, ", "));
        $valores = implode($valor, "', '");

        $query="UPDATE ".$tabla." SET ";

        for ($i = 0; $i < count($campos); $i++) {
            $query.= $campos[$i] . "='". $valor[$i] . "',";
        }

        $query = substr($query, 0, -1);

        if($where != null)
            $query = $query  . " WHERE " . $where;
        $this->log($query);
        try {
            $link->query($query);
        }catch (Exception $e){
            $this->log('eror update: ' . $e->getFile());
        }
//        if (!($result = $link->query($query))) {
//
//            throw new Exception('Error en selectWhere.');
//        }

        $lastid = mysqli_affected_rows($link);

        if($log) {
            $consulta = "INSERT INTO log_inserts (id_usuario, consulta, last_id) VALUES ('" . $_SESSION['USER_ID'] . "','";
            $consulta .= str_replace("'", " ", $query);
            $consulta .= str_replace(",", " ", $query);
            $consulta .= "','" . $lastid . "')";

           $result = $link->query($query);

           $link->close();
        }
        return $lastid;

    }


    public function delete($tabla, $campo, $condicion){
        if($condicion=='')
            return;
        try {
            $link = $this->conectar();

            if($condicion=='*')
                $query = "DELETE FROM ". $tabla;
            else
                $query = "DELETE FROM ". $tabla . " WHERE " . $campo . "='" . $condicion . "'";

//                        //echo $query;

            if (!($result = $link->query($query)))
                throw new Exception('Error en selectWhere.');
            $lastid = mysqli_affected_rows($link);


           $link->close();

            return $lastid;
        } catch (Exception $e) {
//            //echo $query;
//            echo $e->getMessage();
            $this->log('Excepción capturada: ' . $e->getMessage());
        }
        return $lastid;
    }


    public function deleteWhere($tabla, $campo, $where){
        if($where=='')
            return;
        try {
            $link = $this->conectar();

            if($where=='*')
                $query = "DELETE FROM ". $tabla;
            else
                $query = "DELETE FROM ". $tabla . " WHERE " . $where;

//            //echo $query;

            if (!($result = $link->query($query)))
                throw new Exception('Error en selectWhere.');
            $lastid = mysqli_affected_rows($link);


            $link->close();

            return $lastid;
        } catch (Exception $e) {
//            //echo $query;
//            echo $e->getMessage();
            $this->log('Excepción capturada: ' . $e->getMessage());
        }
        return $lastid;
    }


    public function count($tabla, $campo, $where){

    }

    static function OnlyConexion(){

    }

    function cleanstring($string) {

        $string= str_replace("\""," ",$string);
        $string= str_replace("'"," ",$string);
        $string= str_replace("="," ",$string);
        $string= str_replace("%"," ",$string);
        $string= str_replace("\\"," ",$string);
        $string= str_replace("&"," ",$string);
        $string= str_replace(" and "," ",$string);
        $string= str_replace(" AND "," ",$string);
        $string= str_replace("select"," ",$string);
        $string= str_replace("SELECT"," ",$string);
        $string= str_replace("count"," ",$string);
        $string= str_replace("COUNT"," ",$string);
        $string= str_replace("NULL"," ",$string);
        $string= str_replace("null"," ",$string);
        $string= str_replace("like"," ",$string);
        $string= str_replace("LIKE"," ",$string);
        $string= str_replace("insert"," ",$string);
        $string= str_replace("INSERT"," ",$string);
        $string= str_replace("DELETE"," ",$string);
        $string= str_replace("delete"," ",$string);
        $string= str_replace("drop"," ",$string);
        $string= str_replace("DROP"," ",$string);
        $string= str_replace("WHERE"," ",$string);
        $string= str_replace("where"," ",$string);
        $string= str_replace(" OR "," ",$string);
        $string= str_replace(" or "," ",$string);
        $string= str_replace(" Or "," ",$string);
        $string= str_replace(" oR "," ",$string);
        $string= str_replace("||"," ",$string);
        $string = stripslashes($string);
        $string = strip_tags($string);
        return  $string;
    }

    function crear_sql($tabla = null, $campos = null, $valor = null) {

        $link = $this->conectar();

        $columnas = limpiar(implode($campos, ", "));

        $aItems = array();

        foreach( $valor as $request){
            array_push($aItems, $_REQUEST[$request]);
        }

        $valores = implode($aItems, "', '");

        $query="INSERT INTO ".$tabla." (".$columnas.") VALUES ('".$valores."')";

        if (!($result = $link->query($query)))
            throw new Exception('Error en selectWhere.');

        $lastid = mysqli_insert_id($link);
        $consulta = "INSERT INTO log_inserts (id_usuario, consulta, last_id) VALUES ('".$_SESSION['USER_ID']."','";
        $consulta.= str_replace("'"," ",$query);
        $consulta.= str_replace(","," ",$query);
        $consulta.= "','".$lastid . "')";

        $link->query($this->cleanstring($consulta));

       $link->close();

        return $lastid;
    }


    function carga_select($tabla='', $value='', $campos='', $orden='', $where='', $cuantos=1,$title='', $selected=0){

        try {
            $link = $this->conectar();


            $query = 'SELECT '.$value.','.$campos.' FROM ' . $tabla . ($where!="" ? ' WHERE '.$where : " ") ." ". ($orden!="" ? ' ORDER BY '.$orden : " ");

            if (!($result = $link->query($query)))
                throw new Exception('Error en selectWhere.');

            $valores='';

            $n=0;

            while ($row = mysqli_fetch_array($result)){
                $n++;
                if($cuantos == 1 || $cuantos=='')
                    $valores= $row[1];
                else if($cuantos == 2)
                    $valores= ($title[0]!='' ? $title[0]:''). $row[1] . " / ".($title[1]!=''?$title[1]:''). $row[2];
                else if($cuantos == 3)
                    $valores= $row[1] . " / " .$row[2]. " / " .$row[3];

                if($selected!=0 && intval($row[0])==$selected)
                    echo "<option selected data-extra= '".$row[2]."' value='".$row[0]."'>".$valores."</option>";
                else
                    echo "<option data-extra= '".$row[2]."' value='".$row[0]."'>".$valores."</option>";
                $valores='';
            }

           $link->close();

        } catch (Exception $e) {
            $this->log('Excepción capturada: ' . $e->getMessage());
        }



    }

    function aleatorios($length=10,$uc=TRUE,$n=TRUE,$sc=FALSE)
    {
        $source = 'abcdefghijklmnpqrstuvwxyz';
        if($uc==1) $source .= 'ABCDEFGHIJKLMNPQRSTUVWXYZ';
        if($n==1) $source .= '1234567890';
        if($sc==1) $source .= '|@#~$%()=^*+[]{}-_';
        if($length>0)
        {
            $rstr = "";
            $source = str_split($source,1);
            for($i=1; $i<=$length; $i++)
            {
                mt_srand((double)microtime() * 1000000);
                $num = mt_rand(1,count($source));
                $rstr .= $source[$num-1];
            }
        }
        return $rstr;
    }

    function fecha_eur($source){
        $date = date_create($source);

        return date_format($date, 'yyyy-mm-dd');
    }

    function fecha_usa($source){
        $date = new DateTime($source);
        return $date->format('yyyy-mm-dd');    }



    function log($action){

        $ip=$this->ip();
        $link = $this->conectar();
        $query="INSERT INTO logs (log, ip) VALUES ('".$this->cleanstring($action)."','$ip')";
        $link->query($query);
       $link->close();

    }

    function loginsert($action, $lastid){

        $ip=$this->ip();
        $link = $this->conectar();
        $query="INSERT INTO log_inserts (id_usuario, consulta, last_id, ip) VALUES ('".$_SESSION['USER_ID']."', '$action', '$lastid', '$ip');";
        $link->query($query);
       $link->close();

    }
    /** **********************************
    @VISITOR ip
    /** ******************************* **/
    function ip() {
        if     (getenv('HTTP_CLIENT_IP'))       { $ip = getenv('HTTP_CLIENT_IP');       }
        elseif (getenv('HTTP_X_FORWARDED_FOR')) { $ip = getenv('HTTP_X_FORWARDED_FOR'); }
        elseif (getenv('HTTP_X_FORWARDED'))     { $ip = getenv('HTTP_X_FORWARDED');     }
        elseif (getenv('HTTP_FORWARDED_FOR'))   { $ip = getenv('HTTP_FORWARDED_FOR');   }
        elseif (getenv('HTTP_FORWARDED'))       { $ip = getenv('HTTP_FORWARDED');       }
        else { $ip = $_SERVER['REMOTE_ADDR'];        }
        return $ip;
    }


}
function limpiar($c){
    $c= str_replace("\"","",$c);
    $c= str_replace("'","",$c);
    $c= str_replace("=","",$c);
    $c= str_replace("\\","",$c);
    return $c;

}

function leeItems($id_item){

    return "123123123";
}

function editando(){

    if($_SERVER["SERVER_NAME"]=='nuevaweb') return true;

    if(isset($_SESSION['editando']) && validar_sesion()==true)
        return true;
    else
        return false;  // cambiar a false

}

function muestra_item($r, $c){

    // elementos del menu lateral

//array con los items principales del menu
// idmenu, texto, nivel {texto, url}

    $menus=array(
        array('USUARIOS',0,array(
            array('Altas','add-users.php',1),
            array('Edición','edit-users.php',1),
            array('Listados','list-users.php',1)
        )),
        array('REVENDEDORES',0,array(
            array('Altas','add-users.php',1),
            array('Edición','edit-users.php',1),
            array('Listados','list-users.php',1)
        )),
        array('CLIENTES',1,array(
            array('Altas','add-users.php',1),
            array('Edición','edit-users.php',1),
            array('Listados','list-users.php',1)
        )),
        array('EQUIPAMIENTO',1, array(
            array('Altas','add-users.php',1),
            array('Edición','edit-users.php',1),
            array('Listados','list-users.php',1)
        ))
    );


    if($menus[$r][$c][2]<=$_SESSION['USER_LEVEL']){
        return $menus[$r][$c][1];
    }
}



function check_session($nivel=0){

//    if($nivel=='')
//        $nivel=0;

        if(intval($_SESSION['USER_LEVEL'])>intval($nivel))
            header("Location:index.php");

    $now = time();
    if ($now > $_SESSION['expire']) {
        session_destroy();
        header("Location:login.php");
    }

    if(!isset($_SESSION['USER_ID']) || !intval($_SESSION['USER_ID'])>0)
        header("Location:login.php");

    $_SESSION['start'] = time();
    $_SESSION['expire'] = $_SESSION['start'] + (30 * 60);

    return true;
}

function esmovil(){
    $tablet_browser = 0;
    $mobile_browser = 0;

    if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
        $tablet_browser++;
    }

    if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
        $mobile_browser++;
    }

    if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
        $mobile_browser++;
    }

    $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
    $mobile_agents = array(
        'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
        'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
        'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
        'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
        'newt','noki','palm','pana','pant','phil','play','port','prox',
        'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
        'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
        'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
        'wapr','webc','winw','winw','xda ','xda-');

    if (in_array($mobile_ua,$mobile_agents)) {
        $mobile_browser++;
    }

    if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
        $mobile_browser++;
        //Check for tablets on opera mini alternative headers
        $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
            $tablet_browser++;
        }
    }

    if ($tablet_browser > 0 || $mobile_browser > 0) {
        return true;
    } else {
        // do something for everything else
        return false;
    }
}

function timestamp2date($t){
    return date('m/d/Y H:i:s', $t);
}

function timeUp($then){
//Convert it into a timestamp.
    $then = strtotime($then);

//Get the current timestamp.
    $now = time();

//Calculate the difference.
    $difference = $then - $now;

//Convert seconds into days.
    $days = floor($difference / (60*60*24) );

    return $days;
}

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
//        ini_set('max_execution_time', 60);

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
        $this->DoCommand('quit', $result);
        $this->DoCommand(PHP_EOL, $result);
        $this->DoCommand('quit', $result);
        $this->DoCommand(PHP_EOL, $result);
        $this->DoCommand('y', $result);
        $this->DoCommand(PHP_EOL, $result);

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
            $r.=fread($this->fp,6000);
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

?>
