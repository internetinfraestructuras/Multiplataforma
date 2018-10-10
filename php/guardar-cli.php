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
require_once('../config/def_tablas.php');
$util = new util();

check_session(3);
date_default_timezone_set('Etc/UTC');

$post_data = isset($_POST['clientes']) ? $_POST['clientes'] : null;

    // todo: --------------------------------------------
	// cuando el cliente es creado por primera vez
    // todo: --------------------------------------------


	if(isset($_POST['action']) && $_POST['action'] == 'clientes') {


        $email_body = null;
        $array = $required = array();

        // catch post data
        $is_ajax = (isset($_POST['is_ajax']) && $_POST['is_ajax'] == 'true') ? true : false;

        // check post data
        if ($post_data === null) {
            if ($is_ajax === false) {
                _redirect('#alert_mandatory');
            } else {
                die('_mandatory_');
            }
        }


        // EXTRACT DATA FROM POST
        foreach ($post_data as $key => $value) {
            $key_title = ucfirst($key);

            $explode = @explode('_', $key_title);
            if (!isset($explode[1]))
                $explode = @explode('-', $key_title);

            if (isset($explode[1])) {
                $key_title = implode(' ', $explode);
                $key_title = ucwords(strtolower($key_title));
            }

            // se recogen los datos post y se pasan por la funcion que limpia los caracteres suceptibles de generar inyeccion SQL

            $nombre = $util->cleanstring($post_data['nombre']);
            $apellidos = $util->cleanstring($post_data['apellidos']);
            $dni = $util->cleanstring($post_data['dni']);
            $tdoc = $util->cleanstring($post_data['tipodoc']);
            $dir = $util->cleanstring($post_data['dir']);
            $localidad = $util->cleanstring($post_data['localidad']);
            $provincia = $util->cleanstring($post_data['provincia']);
            $region = $util->cleanstring($post_data['region']);
            $iban = $util->cleanstring($post_data['iban']);
            $swift = $util->cleanstring($post_data['swift']);
            $cp = $util->cleanstring($post_data['cp']);
            $tel1 = $util->cleanstring($post_data['tel1']);
            $tel2 = $util->cleanstring($post_data['tel2']);
            $email = $util->cleanstring($post_data['email']);
            $notas = $util->cleanstring($post_data['notas']);
            $banco = $util->cleanstring($post_data['banco']);
            $lopd = $util->cleanstring($post_data['lopd']);
            $tcli = $util->cleanstring($post_data['tipocli']);
            $fnac = $util->cleanstring($post_data['nacimiento']);
            $dirbanco = $util->cleanstring($post_data['dirbanco']);
            $nacion = $util->cleanstring($post_data['nacion']);
        }

        $documentos = $_POST['documentos'];

        $campos=array('NOMBRE','APELLIDOS','DNI','TIPO_DOCUMENTO','DIRECCION','LOCALIDAD','PROVINCIA',
            'COMUNIDAD','IBAN','SWIFT','ID_EMPRESA','CP','FIJO','MOVIL','EMAIL','FECHA_ALTA','NOTAS','BAJA',
            'BANCO','ID_CONSENTIMIENTO','ID_TIPO_CLIENTE','FECHA_NACIMIENTO','DIRECCION_BANCO','NACIONALIDAD');

        $alta=date("Y-m-d");

        $values = array( $nombre, $apellidos, $dni, $tdoc, $dir, $localidad, $provincia, $region, $iban, $swift,
            $_SESSION['REVENDEDOR'], $cp, $tel1, $tel2, $email, $alta, $notas,0,  $banco,  $lopd,$tcli,$fnac,$dirbanco, $nacion);

        // llama a la funcion insertInto de la clase util que recibe la tabla (string) y dos arrays (campos y valores)

        $result = $util->insertInto('clientes', $campos, $values);
        $util->log('Se ha creado un cliente'.$_SESSION['REVENDEDOR'].' con dni :'.$dni.' con el id:'.$result);
        if(intval($result)>0){
            foreach ($documentos as $doc) {
                $util->consulta('INSERT INTO clientes_documentos (ID_CLIENTE,	ID_TIPO_DOCUMENTO,DOCUMENTO, ID_EMPRESA) 
                                        VALUES ("'.$result.'","'.$doc[0].'","'.CLI_RUTA_DOCUMENTOS.$doc[1].'","'.$_SESSION['REVENDEDOR'].'")');
            }
        }

        echo $result;

    } else {

        // todo: --------------------------------------------
        // cuando el cliente es editado
        // todo: --------------------------------------------
//        $post_data = isset($_POST['clientes']) ? $_POST['clientes'] : null;


        if (
            (isset($_POST['action']) && $_POST['action'] == 'edit')
            &&
            ($post_data['id'] != '')
            &&
            (md5($post_data['id']) == $post_data['hash'])
        ) {

            $id = $post_data['id'];

            $nombre = $util->cleanstring($post_data['nombre']);
            $apellidos = $util->cleanstring($post_data['apellidos']);
            $dni = $util->cleanstring($post_data['dni']);
            $tipodoc = $util->cleanstring($post_data['tipodoc']);
            $dir = $util->cleanstring($post_data['direccion']);
            $localidad = $util->cleanstring($post_data['localidad']);
            $provincia = $util->cleanstring($post_data['provincia']);
            $region = $util->cleanstring($post_data['region']);
            $iban= $util->cleanstring($post_data['iban']);
            $swift= $util->cleanstring($post_data['swift']);
            $cp = $util->cleanstring($post_data['cp']);
            $tel1 = $util->cleanstring($post_data['tel1']);
            $tel2 = $util->cleanstring($post_data['tel2']);
            $email = $util->cleanstring($post_data['email']);
            $notas = $util->cleanstring($post_data['notas']);
            $banco= $util->cleanstring($post_data['banco']);
            $consentimiento = $util->cleanstring($post_data['lopd']);
            $tipocli = $util->cleanstring($post_data['tipocli']);
            $nacimiento = $util->cleanstring($post_data['nacimiento']);
            $dirbanco = $util->cleanstring($post_data['dirbanco']);
            $nacion = $util->cleanstring($post_data['nacion']);


//            if (isset($_POST['region'])) {
            $values = array($nombre, $apellidos,$dni, $tipodoc, $dir, $localidad, $provincia,
                $region, $iban, $swift, $_SESSION['REVENDEDOR'], $cp, $tel1, $tel2, $email, $notas,
                $banco, $consentimiento, $tipocli, $nacimiento, $dirbanco, $nacion);

            $campos=array('NOMBRE','APELLIDOS','DNI','TIPO_DOCUMENTO','DIRECCION','LOCALIDAD','PROVINCIA',
                'COMUNIDAD','IBAN','SWIFT','ID_EMPRESA','CP','FIJO','MOVIL','EMAIL','NOTAS',
                'BANCO','ID_CONSENTIMIENTO','ID_TIPO_CLIENTE','FECHA_NACIMIENTO','DIRECCION_BANCO','NACIONALIDAD');

            $result = $util->update('clientes', $campos, $values, "ID=" . $id ." AND ID_EMPRESA = ". $_SESSION['REVENDEDOR']);

            $documentos = $_POST['documentos'];

                foreach ($documentos as $doc) {
                    $util->consulta('INSERT INTO clientes_documentos (ID_CLIENTE,	ID_TIPO_DOCUMENTO,DOCUMENTO, ID_EMPRESA) 
                                        VALUES ("'.$id.'","'.$doc[0].'","'.CLI_RUTA_DOCUMENTOS.$doc[1].'","'.$_SESSION['REVENDEDOR'].'")');
                }


            echo $result;

            $util->log('El usuario:' . $_SESSION['USER_ID'] . ' ha modificado el cliente: ' . $dni . ' con el resultado:' . $result);
        } else {
            echo "nose";
            die();
        }
    }
?>