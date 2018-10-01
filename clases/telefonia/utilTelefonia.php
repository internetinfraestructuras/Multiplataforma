<?php
/**
 * Created by PhpStorm.
 * User: rcc
 * Date: 7/12/17
 * Time: 16:39
 */


if (!isset($_SESSION)) {@session_start();}

require_once('../../config/define.php');
require_once('../../config/def_tablas.php');

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

date_default_timezone_set('Europe/Madrid');

class UtilT {

    function generateRandomString($length = 20) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString='';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


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
        $link = new mysqli(DB_TELEFONIA_SERVER, DB_TELEFONIA_USER, DB_TELEFONIA_PASSWORD,DB_TELEFONIA_DATABASENAME);

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


            //echo $query;
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

            //echo "quewryyyy $query";

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
            //echo $query;
            if (!($result = $link->query($query)))
                throw new Exception('Error en selectJoin.');

            $link->close();

            return $result;

        } catch (Exception $e) {
            $this->log('Error selectJoin: ' . $query);
        }
        return $result;

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
           // echo $query;
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

             //echo $query;

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
        return $fieldNames;

    }

    public function selectMax($tabla, $campo, $where){

        $link = $this->conectar();

        $query = 'SELECT max('. $campo . ') FROM ' . $tabla;

        if($where!='')
            $query.= " where " . $where;
//echo $query;
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

        //mod by paco
        $query.= " order by $campo desc limit 1 ";

       // echo $query;
        if (!($result = $link->query($query)))
            throw new Exception('Error en selectLast.');

        $row = mysqli_fetch_array($result);

       $link->close();

        return $row[0];

    }


    public function limpiar($c)
    {
        $c = str_replace("\"", "", $c);
        $c = str_replace("'", "", $c);
        $c = str_replace("=", "", $c);
        $c = str_replace("\\", "", $c);
        return $c;

    }

    public function insertInto($tabla, $campos, $valor, $log=true){

        //echo "insert intooo";

        try {


            $link = $this->conectar();

            //echo "hola-2";
            //var_dump($campos);
            $columnas = $this->limpiar(implode($campos, ", "));

            //echo "hola-1";
            $aItems = array();

            //echo "hola0";
            $valores = implode($valor, "', '");

            //echo "hola";

            $query="INSERT INTO ".$tabla." (".$columnas.") VALUES ('".$valores."')";
            //echo "<br/>";
            //echo $query."<br><br><br><br>";
            $query = str_replace("º","",$query);
            if (!($result = $link->query($query)))
                throw new Exception('Error en selectWhere.');

            //$lastid = mysqli_insert_id($link);
            //cambio para la maravillosa base de datos de telefonia sin ids... yea
            $lastid = mysqli_affected_rows($link);

            $this->log( $query);

            $link->close();

            return $lastid;

        } catch (Exception $e) {
            $this->log('Error Insert: ' . $query);
        }
        return $lastid;
    }


    public function update($tabla, $campos, $valor, $where, $log=true){


        if($where=='')
            return;

//        echo "here";
        $link = $this->conectar();

        $columnas = $this->limpiar(implode($campos, ", "));
        $valores = implode($valor, "', '");

        $query="UPDATE ".$tabla." SET ";

        for ($i = 0; $i < count($campos); $i++) {
            $query.= $campos[$i] . "='". $valor[$i] . "',";
        }

        $query = substr($query, 0, -1);


        if($where != null)
            $query = $query  . " WHERE " . $where;
        $this->log($query);

        //echo $query;

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

//                        echo $query;

            if (!($result = $link->query($query)))
                throw new Exception('Error en selectWhere.');
            $lastid = mysqli_affected_rows($link);


           $link->close();

            return $lastid;
        } catch (Exception $e) {
//            echo $query;
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

          //  echo $query;

            if (!($result = $link->query($query)))
                throw new Exception('Error en selectWhere.');
            $lastid = mysqli_affected_rows($link);


            $link->close();

            return $lastid;
        } catch (Exception $e) {
//            echo $query;
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


    function carga_select($tabla='', $value='', $campos='', $orden='', $where='', $cuantos='',$title=''){

        try {
            $link = $this->conectar();


            $query = 'SELECT '.$value.','.$campos.' FROM ' . $tabla . ($where!="" ? ' WHERE '.$where : " ") ." ". ($orden!="" ? ' ORDER BY '.$orden : " ");

            if (!($result = $link->query($query)))
                throw new Exception('Error en selectWhere.');

            $valores='';

            while ($row = mysqli_fetch_array($result)){
                if($cuantos == 1 || $cuantos=='')
                    $valores= $row[1];
                else if($cuantos == 2)
                    $valores= ($title[0]!='' ? $title[0]:''). $row[1] . " / ".($title[1]!=''?$title[1]:''). $row[2];
                else if($cuantos == 3)
                    $valores= $row[1] . " / " .$row[2]. " / " .$row[3];

                echo "<option value='".$row[0]."'>".$valores."</option>";
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
?>
