<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 14/02/2018
 * Time: 12:47
 */

require_once('db_config.php');

define('DDBB','pruebas.');

// cabeceras de los ficheros html
define('AUTOR','Internet Infraestructuras S.L.');
define('OWNER','AT Control');

define('DEF_REVENDEDORES','Revendedores');
define('DEF_REVENDEDOR','Revendedor');
define('DEF_USUARIOS','Usuarios');
define('DEF_CLIENTES','Clientes');
define('DEF_ANEXOS','Anexos');
define('DEF_SERVICIOS','Servicios');
define('DEF_PAQUETES','Paquete');
define('DEF_PRODUCTO','Producto');
define('DEF_TIPOS','Tipos de productos');
define('DEF_MODELOS','Modelos de productos');
define('DEF_ATRIBUTOS','Atributos de productos');
define('DEF_PROVEEDORES','Proveedores');
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
define('MNU_ITEM_8','Almacén');
define('MNU_ITEM_9','Ventas');
define('MNU_ITEM_10','Servicios');

date_default_timezone_set('Europe/Madrid');


// comandos SNMP

define('ROOT','.1.3.6.1');
define('HUAWEI','1.3.6.1.4.1.2011');
define('SYS_DESCRYPT','.1.3.6.1.2.1');
define('VLANS','.1.3.6.1.4.1.2011.5.6.1.1.1.2');
define('HARDWARE','.1.3.6.1.4.1.2011.6');
define('HARDWARE_CFG','.1.3.6.1.4.1.2011.6.10');
define('TEMP','.1.3.6.1.4.1.2011.6.1.1.2.1.2.1');
define('SYS_UP','.1.3.6.1.2.1.1.3');
define('ONTS','1.3.6.1.4.1.2011.6.128.1.1.2.43.1');

//profiles
define('PROFILES','.1.3.6.1.4.1.2011.6.128.1.1.2.142.1.2.4194307840.1');
define('PROFILES_GENERIC','.1.3.6.1.4.1.2011.6.128.1.1.2.43.1.7');
define('HG8546M','.1.3.6.1.4.1.2011.6.128.1.1.2.43.1.8');


define('hwHdsl','.1.3.6.1.4.1.2011.6');


define('INTERFACE_NAME','.1.3.6.1.2.1.31.1.1.1.1');
define('VOLTAGE','1.3.6.1.4.1.2011.6.128.1.1.2.51.1.5');
define('INTERFACES','.1.3.6.1.2.1.17.1.4.1.2');
define('TEST','.1.3.6.1.2.1.65');


define("ESPACIO","\040");

