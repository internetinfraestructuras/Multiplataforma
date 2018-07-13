<?php
/**
 * @version		v1.0.0
 * @author		Dorin Grigoras [www.stepofweb.com]
 * @date		Thursday, May 21, 2015
**/	date_default_timezone_set('Etc/UTC');
	@ini_set('display_errors', 0);
	@ini_set('track_errors', 0);

	require('config.inc.php');


/** ******************************** **
 *	@revende FORM
 ** ******************************** **/

	// Check Action First!
	if(isset($_POST['action']) && $_POST['action'] == 'revendedores') {
        $email_body = null;
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

        // EXTRACT DATA FROM POST
        foreach ($post_data as $key => $value) {
            $key_title = ucfirst($key);

            /*
                Split words
                Converting: first_name => First Name
            */
            $explode = @explode('_', $key_title);
            if (!isset($explode[1]))
                $explode = @explode('-', $key_title);

            if (isset($explode[1])) {
                $key_title = implode(' ', $explode);
                $key_title = ucwords(strtolower($key_title));
            }


            // ----- extract required -----
            if (isset($post_data[$key]['required'])) {
                $required[] = $key;

                // We need the user email&name for AddReplyTo on phpmailer!
                if ($key == 'name')
                    $array['name'] = $post_data[$key]['required'];

                if ($key == 'email')
                    $array['email'] = $post_data[$key]['required'];

                // Build Mail BODY (if succes, use it)
                $email_body .= "<b>{$key_title}:</b> {$post_data[$key]['required']} <br>";


                // ----- extract non-required -----
            } else {
                $non_required[] = $key;

                // We need the user email&name for AddReplyTo on phpmailer!
                if ($key == 'name')
                    $array['name'] = $post_data[$key];

                if ($key == 'email')
                    $array['email'] = $post_data[$key];

                // Build Mail BODY (if succes, use it)
                $email_body .= "<b>{$key_title}:</b> {$post_data[$key]} <br>";
            }


        }


        // Check for required! Redirect if something found empty!
        foreach ($required as $req) {
            if (strlen(trim($post_data[$req]['required'])) < 2) {
                if ($is_ajax === false) {
                    _redirect('#alert_mandatory');
                    exit;
                } else {
                    die('_mandatory_');
                }
            }
        }


        // Check Email
        $array['email'] = ckmail($array['email']);
        if ($array['email'] === false) {
            if ($is_ajax === false) {
                _redirect('#alert_mandatory');
                exit;
            } else {
                die('_mandatory_');
            }
        }


        // ATTACHMENT
        if (isset($_FILES['revende']['name']['attachment']) && $_FILES['revende']['name']['attachment'] != '') { // name|type|tmp_name|error|size|

            $target_file = UPLOAD_FOLDER . basename($_FILES['revende']['name']['attachment']);
            $rename = time() . '_' . basename($_FILES['revende']['name']['attachment']);
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

            // Check if image file is a actual image or fake image
            if (isset($_POST["submit"])) {
                $check = getimagesize($_FILES['revende']['tmp_name']['attachment']);
                if ($check !== false) {
                    echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    echo "Ese fichero no es una imagen.";
                    $uploadOk = 0;
                }
            }
            // Check if file already exists
            if (file_exists($target_file)) {
                echo "Por favor renombra el fichero logotipo.";
                $uploadOk = 0;
            }
            // Check file size
            if ($_FILES['revende']['size']['attachment'] > UPLOAD_MAX_SIZE) {
                echo "El logotipo es demasiado grande.";
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
                echo "Error no se ha podido cargar el logotipo";
                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES['revende']['tmp_name']['attachment'], UPLOAD_FOLDER . $rename)) {

                    $email_body .= '<b>Attachment:</b> <a href="' . UPLOAD_FOLDER_URL . $rename . '">' . "{$rename}</a> <br>";

                } else {

                    echo "Error no se ha podido cargar el logotipo";

                }
            }

        }


        // Visitor IP:
        $ip = ip();


        // DATE
        $date = date('l, d F Y , H:i:s');
        $email_body .= "{$date} <br>";

    }



/** ********************************** 
 @CHECK EMAIL
/** ******************************* **/
	function ckmail($email) {
		$email = trim(strtolower($email));
		if(preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/',trim($email))){
			return $email;
		} else { return false; }
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
?>