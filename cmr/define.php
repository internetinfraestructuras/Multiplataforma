<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 14/02/2018
 * Time: 12:47
 */

require_once('db_config.php');

// cabeceras de los ficheros html
define('AUTOR','Ruben Corrales, 2018 para AT Control');
define('OWNER','AT Control');

define('DEF_REVENDEDORES','Revendedores');
define('DEF_REVENDEDOR','Revendedor');
define('DEF_USUARIOS','Usuarios');
define('DEF_CLIENTES','Clientes');
define('DEF_ALMACEN','Almacén');
define('DEF_PAQUETES','Paquete');
define('DEF_SERVICIOS','Servicios');
define('DEF_OLT','Cabeceras');
define('DEF_PROVISIONES','Provisiones');

//Elementos principales del menu lateral izquierdo
define('MNU_ITEM_1','Usuarios');
define('MNU_ITEM_2','Revendedores');
define('MNU_ITEM_3','Clientes');
define('MNU_ITEM_4','Configuración');
define('MNU_ITEM_5','Aprovisionamiento');
define('MNU_ITEM_6','Cabeceras');
define('MNU_ITEM_7','Herramientas');

date_default_timezone_set('Europe/Madrid');


define("ESPACIO","\040");

