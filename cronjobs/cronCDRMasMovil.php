<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 18/10/2018
 * Time: 10:55
 */

require_once ('../config/util.php');
require_once ('../clases/masmovil/MasMovilAPI.php');

//SE DESCARGA EL CDR DEL DÍA ANTERIOR DE MAS MOVIL SI ESTÁ EL FICHERO MENSUAL TAMBIEN SE LO DESCARGARÁ.
//LANZAR CRONTABS TODOS LOS DIAS A LAS 12:00 MAS O MENOS QUE ES CUANDO NORMALMENTE CUELGAN LOS CDRS.
$masMovil=new MasMovilAPI();
$masMovil->getCDRFTP();
