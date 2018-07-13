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
check_session(01);

date_default_timezone_set('Etc/UTC');

	require('config.inc.php');


	// Check Action First!
	if(isset($_POST['action']) && $_POST['action'] == 'revendedores') {


        $array = $required = array();

        // catch post data
        $post_data = isset($_POST['revende']) ? $_POST['revende'] : null;
        $is_ajax = (isset($_POST['is_ajax']) && $_POST['is_ajax'] == 'true') ? true : false;

        // check post data
        if ($post_data === null) {
            if ($is_ajax === false) {
                _redirect('#alert_mandatory');
            } else {
                die('_mandatory_');
            }
        }

        // logo
        if (isset($_FILES['revende']['name']['logo']) && $_FILES['revende']['name']['logo'] != '') { // name|type|tmp_name|error|size|

            $target_file = UPLOAD_FOLDER . basename($_FILES['revende']['name']['logo']);
            $rename = time() . '_' . basename($_FILES['revende']['name']['logo']);
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

            // Check if image file is a actual image or fake image
            if (isset($_POST["submit"])) {
                $check = getimagesize($_FILES['revende']['tmp_name']['logo']);
                if ($check !== false) {
                    echo "Fichero de imagen correcto - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    echo "Ese fichero no es una imagen.";
                    $uploadOk = 0;
                }
            }
            // Check if file already exists
            if (file_exists($target_file)) {
                echo "Por favor renombra el fichero logo.";
                $uploadOk = 0;
            }
            // Check file size
            if ($_FILES['revende']['size']['logo'] > UPLOAD_MAX_SIZE) {
                echo "El logo es demasiado grande.";
                $uploadOk = 0;
            }
            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" && $imageFileType != "zip" && $imageFileType != "rar"
                && $imageFileType != "pdf") {
                echo "Solo se permiten ficheros de tipo JPG, JPEG, PNG & GIF";
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Error no se ha podido cargar el logo";
                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES['revende']['tmp_name']['logo'], UPLOAD_FOLDER . $rename)) {

                    $logo =  UPLOAD_FOLDER_URL . $rename ;

                } else {

                    echo "Error no se ha podido cargar el logo";

                }
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

            $nombre = $util->cleanstring($post_data['nombre']);
            $apellidos = $util->cleanstring($post_data['apellidos']);
            $dni = $util->cleanstring($post_data['dni']);
            $empresa = $util->cleanstring($post_data['empresa']);
            $dir = $util->cleanstring($post_data['dir']);
            $cp = $util->cleanstring($post_data['cp']);
            $region = $util->cleanstring($post_data['region']);
            $provincia = $util->cleanstring($post_data['provincia']);
            $localidad = $util->cleanstring($post_data['localidad']);
            $email = $util->cleanstring($post_data['email']);
            $tel1 = $util->cleanstring($post_data['tel1']);
            $tel2 = $util->cleanstring($post_data['tel2']);
            $tel2 = $util->cleanstring($post_data['tel2']);
            $notas = $util->cleanstring($post_data['notas']);
            $website = $util->cleanstring($post_data['website']);
            $usuario = $util->cleanstring($post_data['usuario']);
            $clave =  md5($util->cleanstring($post_data['pass1']));
            //genero un hash en md5 con la concatenacion del Dni y una cadena aleatoria de 20 digitos
            $hash = md5($dni . $util->aleatorios(20));
        }


        $values = array($hash, $dni, $nombre, $apellidos, $empresa, $dir, $localidad, $provincia, $region, $cp, $tel1, $tel2, $email, $website, $logo,$notas);

        $result = $util->insertInto('revendedores', $t_revendedores, $values);
        $util->log('El administrador:'.$_SESSION['USER_ID'].' ha creado el revendedor:'.$dni.' con el resultado:'.$result);


        if(intval($result)>0){

            $t_usuarios=array('dni','nombre','apellidos','email','telefono','nivel','clave','notas','revendedor','usuario','hash');

            $values = array( $dni, $nombre, $apellidos, $email, $tel1, '1', $clave, $notas, $result,$usuario,$hash );

            $result = $util->insertInto('usuarios', $t_usuarios, $values);

            if($is_ajax === false) {
                _redirect('#alert_success');
                exit;
            } else {
                die('_success_');
            }
        } else{
            if($is_ajax === false) {
                _redirect('#alert_failed');
                exit;
            } else {
                die('_failed_');
            }
        }

    }

    if(
        (isset($_POST['oper']) && $_POST['oper'] == 'edit')
        &&
        (isset($_POST['id']) && $_POST['id'] != '')
    )
    {

        $id = $_POST['id'];
        $dni = $util->cleanstring($_POST['dni']);
        $nombre = $util->cleanstring($_POST['nombre']);
        $apellidos = $util->cleanstring($_POST['apellidos']);
        $empresa = $util->cleanstring($_POST['empresa']);
        $dir = $util->cleanstring($_POST['direccion']);
        $cp = $util->cleanstring($_POST['cp']);
        $email = $util->cleanstring($_POST['email']);
        $tel1 = $util->cleanstring($_POST['tel1']);
        $tel2 = $util->cleanstring($_POST['tel2']);
        $notas = $util->cleanstring($_POST['notas']);
        $website = $util->cleanstring($_POST['web']);


        $values = array( $dni,$nombre, $apellidos, $empresa, $dir, $cp, $tel1, $tel2, $email, $website,$notas);
        $campos=array('dni','nombre','apellidos','empresa','direccion', 'cp','tel1','tel2','email','web','notas');

        $result = $util->update('revendedores', $campos, $values, "id='$id'");
        $util->log('El administrador:'.$_SESSION['USER_ID'].' ha modificado el revendedor:'.$dni.' con el resultado:'.$result);

        if(intval($result)>0){
                _redirect('#alert_success');

        } else {
            _redirect('#alert_failed');
        }
    }

function _redirect($hash) {

    $HTTP_REFERER = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

    if($HTTP_REFERER === null)
        die("Invalid Referer. Output Message: {$hash}");

    header("Location: {$HTTP_REFERER}{$hash}");
    exit;
}



?>