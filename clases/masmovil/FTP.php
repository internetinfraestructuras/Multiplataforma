<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 05/09/2018
 * Time: 11:05
 */

class FTP
{

    var $ftpServer;
    var $userFtp;
    var $passFtp;
    var $conexion;
    var $rutaFichero="cdrs/mvno_216358-";

    public function __construct()
    {
        $this->ftpServer="ftp.xtratelecom.es";
        $this->userFtp="c216358";
        $this->passFtp="8roA1WK6";
    }

    /*FUNCION DE CONECTAR AL SERVIDOR Y HACER LOGIN EN EL FTP*/
    public function conectar()
    {
        $this->conexion = ftp_connect($this->ftpServer) or die("No se pudo conectar a $this->ftpServer");
        $login_result = ftp_login($this->conexion, $this->userFtp, $this->passFtp);
    }


    public function descargarCDRDia()
    {

        $hoy = date("Ymd");
        $fecha= $this->dia_anterior($hoy);
        $localFile="$fecha.txt";
        $fecha="20180903";
        if (ftp_get($this->conexion, $localFile, $this->rutaFichero.$fecha."_deg.txt", FTP_BINARY))
        {
            echo "El fichero del CDR se ha descargado correctamente! $localFile\n";
        }
        else {
            echo "Error al descargar el CDR!! \n";
        }
    }

    function dia_anterior($fecha)
    {
        $sol = (strtotime($fecha) - 3600);
        return date('Ymd', $sol);
    }
}