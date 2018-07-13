<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 26/02/2018
 * Time: 10:17
 */


include_once("php/ssh2.php");

$ip='89.140.16.7';

if(!($con = ssh2_connect($ip, 2222))){
    echo'No se puede conectar con la máquina '.$ip;
} else {
    //Autentificación
    if(!ssh2_auth_password($con, "userssh", "userssh")) {
        echo'Fallo de autentificación en la máquina '.$ip;
    } else {
        //Ejecución del comando
        if(!($stream = ssh2_exec($con, "cmd /C cd C:\bat\script && script.bat" )) ){
            echo 'Fallo de ejecución de comando en la máquina '.$ip;
        } else {
            //echo "Ejecutado comando 2";
            stream_set_blocking( $stream, true );
            $data = "";
            while( $buf = fread($stream,4096) ){
                $data .= $buf;
                echo "".$buf;
            }
            fclose($stream);
        }
    }
}

if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");

// log in at server on port 22

if(!($con = ssh2_connect($ip, 22))){

    echo "fail: unable to establish connection\n";

} else {

// try to authenticate with username root, password secretpassword

    if(!ssh2_auth_password($con, "user", "pass")) {

        echo "fail: unable to authenticate\n";

    } else {

// allright, we're in!

        echo "okay: logged in...\n";

// execute a command

        if(!($stream = ssh2_exec($con, "ls -al" )) ){

            echo "fail: unable to execute command\n";

        } else{

// collect returning data from command

            echo "Executed ls -la\n";

            stream_set_blocking( $stream, true );

            $data = "";

            while( $buf = fread($stream,4096) ){

                $data .= $buf;

                echo "".$buf;

            }

            fclose($stream);

        }

    }

}