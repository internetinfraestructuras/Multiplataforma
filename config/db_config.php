<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 14/02/2018
 * Time: 12:36
 */
error_reporting(E_ALL);
ini_set("display_errors", 0);


// compruebo si estas corriendo en el servidor local o en produccion

    define ('DB_SERVER' , '5.40.80.11');
    define ('DB_DATABASENAME', 'multiplataforma');
    define ('DB_USER', 'root');
    define ('DB_PASSWORD', 'telereq1430*sql');

define ('DB_TELEFONIA_SERVER' , '89.140.16.198');
define ('DB_TELEFONIA_DATABASENAME', 'gestioncdr');
define ('DB_TELEFONIA_USER', 'testerp');
define ('DB_TELEFONIA_PASSWORD', 'erpDirect18');

