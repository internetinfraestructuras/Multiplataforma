<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 02/04/2018
 * Time: 13:13
 */


//        ini_set('display_errors',1);
//        error_reporting('E_ALL');

        if (!isset($_SESSION)) {@session_start();}

        require_once('../config/define.php');
        require_once('../config/util.php');
        $util = new util();
        check_session(0);

        if(
            isset($_POST['id']) && $_POST['id']!='' &&
            isset($_POST['password']) && $_POST['password']!='' &&
            (md5($_POST['id'])==$_POST['password']) &&
            $_SESSION['USER_LEVEL']==0 &&
            $_SESSION['USER_ID'] == 1
        ) {

            $where = ' id = '. $_POST['id'];
            $result = $util->selectWhere("usuarios", array("id", "nombre", "apellidos", "nivel", "revendedor"), $where);
            $row = mysqli_fetch_array($result);
             if (intval($row[0]) > 0) {
                $_SESSION['USER_ID'] = $row['id'];
                $_SESSION['NOM_USER'] = $row['nombre'] . " " . $row['apellidos'];
                $_SESSION['USER_LEVEL'] = $row['nivel'];
                $_SESSION['REVENDEDOR'] = $row['revendedor'];
                $_SESSION['start'] = time();
                $_SESSION['expire'] = $_SESSION['start'] + (30 * 60);
                header("Location:../index.php");
            } else {
                $login_fail = true;
            }
        } else echo "error";

    ?>