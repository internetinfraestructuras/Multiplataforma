<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 10:19
 */


/*
 * LOGIN
 * url:     http://ftth.internetinfraestructuras.es/cmr/guardar-reseller.php
 *
 * Espera:
 *
 *      $_POST['nombre']
        $_POST['apellidos']
        $_POST['dni']               * requerido
        $_POST['empresa']
        $_POST['dir']
        $_POST['cp']
        $_POST['region']
        $_POST['provincia']
        $_POST['localidad']
        $_POST['email']             * requerido
        $_POST['tel1']
        $_POST['tel2']
        $_POST['tel2']
        $_POST['notas']
        $_POST['website']
        $_POST['usuario']           * requerido
        $_POST['password']   md5    * requerido
        $_POST['check']        md5(dni)     para su comprobación        * requerido
 *
 * Devuelve:
 *
         'result => '',
            'id' => ,
            'token' =>

        result puede ser:
        ok
        error 1     dni duplicado
        error 2     email duplicado
        error 3     usuario duplicado

        --------------------PRUEBA POSTMAN ----------------------


 */
require_once('util.php');
require_once('def_tablas.php');
$util = new util();


date_default_timezone_set('Etc/UTC');


	// Check Action First!
	if(isset($_POST['action']) && $_POST['action'] == 'addreseller') {

        $array = $required = array();

        
        // logo
        if (isset($_FILES['name']['logo']) && $_FILES['name']['logo'] != '') { // name|type|tmp_name|error|size|

            $target_file = UPLOAD_FOLDER . basename($_FILES['name']['logo']);
            $rename = time() . '_' . basename($_FILES['name']['logo']);
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

            // Check if image file is a actual image or fake image
            if (isset($_POST["submit"])) {
                $check = getimagesize($_FILES['tmp_name']['logo']);
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
            if ($_FILES['size']['logo'] > UPLOAD_MAX_SIZE) {
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
                if (move_uploaded_file($_FILES['tmp_name']['logo'], UPLOAD_FOLDER . $rename)) {

                    $logo =  UPLOAD_FOLDER_URL . $rename ;

                } else {

                    echo "Error no se ha podido cargar el logo";

                }
            }

        }

        $nombre = $util->cleanstring($_POST['nombre']);
        $apellidos = $util->cleanstring($_POST['apellidos']);
        $dni = $util->cleanstring($_POST['dni']);
        $empresa = $util->cleanstring($_POST['empresa']);
        $dir = $util->cleanstring($_POST['dir']);
        $cp = $util->cleanstring($_POST['cp']);
        $region = $util->cleanstring($_POST['region']);
        $provincia = $util->cleanstring($_POST['provincia']);
        $localidad = $util->cleanstring($_POST['localidad']);
        $email = $util->cleanstring($_POST['email']);
        $tel1 = $util->cleanstring($_POST['tel1']);
        $tel2 = $util->cleanstring($_POST['tel2']);
        $tel2 = $util->cleanstring($_POST['tel2']);
        $notas = $util->cleanstring($_POST['notas']);
        $website = $util->cleanstring($_POST['website']);
        $usuario = $util->cleanstring($_POST['usuario']);
        $clave =  md5($util->cleanstring($_POST['password']));
        //genero un hash en md5 con la concatenacion del Dni y una cadena aleatoria de 20 digitos

        $token = bin2hex(openssl_random_pseudo_bytes(32));

        if(isset($_POST['dni']) && isset($_POST['email']) && isset($_POST['usuario']) && isset($_POST['password']) && (md5($_POST['dni'])==$_POST['check'])) {

            $values = array($token, $dni, $nombre, $apellidos, $empresa, $dir, $localidad, $provincia, $region, $cp, $tel1, $tel2, $email, $website, $logo, $notas,$token);

            $result = $util->insertInto('revendedores', $t_revendedores, $values);
            $util->log('La API ha creado el reseller:' . $dni . ' con el resultado:' . $result);

            $aItems = array();
            if (intval($result) > 0) {

                $t_usuarios = array('dni', 'nombre', 'apellidos', 'email', 'telefono', 'nivel', 'clave', 'notas', 'revendedor', 'usuario');

                $values = array($dni, $nombre, $apellidos, $email, $tel1, '1', $clave, $notas, $result, $usuario);

                $result = $util->insertInto('usuarios', $t_usuarios, $values);

                $aItem = array(
                    'result' => 'ok',
                    'id' => $result,
                    'token' => $token
                );

            } else {
                $aItem = array(
                    'result' => 'failed',
                    'id' => 0,
                    'token' => ""
                );
            }


            array_push($aItems, $aItem);
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($aItems);
        } echo "faltan datos requericos";

    } else echo "falta algo";
?>