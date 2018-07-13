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

if( in_array( $_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' ) ) ) {
    define ('DB_SERVER' , 'localhost');
    define ('DB_DATABASENAME', 'multiplataforma');
    define ('DB_USER', 'root');
    define ('DB_PASSWORD', '');
} else {
    define ('DB_SERVER' , '127.0.0.1');
    define ('DB_DATABASENAME', 'fibra');
    define ('DB_USER', 'root');
    define ('DB_PASSWORD', 'telereq1430*sql');

}
