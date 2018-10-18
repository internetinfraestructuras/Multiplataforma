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

        $mensual = date('m');

        $anio=date('Y');

        if($mensual==1)
            $mensual=12;
        else
            $mensual--;


        $mensual=str_pad($mensual,2,'0',STR_PAD_LEFT);

        $mensual=$anio.$mensual;

        $fecha= $this->dia_anterior($hoy);
        $localFile="$fecha.txt";
        $localFileMensual=$mensual."_mes.txt";


        if(!file_exists("../../cdr/".$localFile))
        {
            if (ftp_get($this->conexion, "./../cdr/".$localFile, $this->rutaFichero.$fecha."_deg.txt", FTP_BINARY))
                echo "Se ha descargado correctamente el fichero DIARIO!!!";
            else
                echo "Error al obtener el CDR diario";
        }

       if(!file_exists("../../cdr/".$localFileMensual))
        {
            if (ftp_get($this->conexion, "./../cdr/".$localFileMensual, $this->rutaFichero.$localFileMensual, FTP_BINARY))
                echo "Se ha descargado correctamente el fichero MENSUAL!!1";
            else
                echo "Error al obtener el CDR mensual";
        }




    }

    function dia_anterior($fecha)
    {
        $sol = (strtotime($fecha) - 3600);
        return date('Ymd', $sol);
    }
}