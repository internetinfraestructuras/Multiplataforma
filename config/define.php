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
define('ID_NUMERO_MOVIL',48);
define('ID_PROVEEDOR_MASMOVIL',14);
define('ID_PROVEEDOR_AIRENETWORKS',15);
define('DEF_REVENDEDORES','Revendedores');
define('DEF_FACTURACION','Facturación');
define('DEF_REVENDEDOR','Revendedor');
define('DEF_USUARIOS','Usuarios');
define('DEF_CLIENTES','Clientes');
define('DEF_PORTA_CONF','Portabilidades');
define('DEF_LISTADO_MOVILES','Líneas móviles activas');
define('DEF_CDR','Líneas móviles activas');
define('DEF_CAMPANAS','Campañas');
define('DEF_ANEXOS','Anexos');
define('DEF_ALMACEN','Almacén');
define('DEF_ORDENES','Ordenes de trabajo');
define('DEF_CONTRATOS','Contratos');
define('DEF_SERVICIOS','Servicios');
define('DEF_PAQUETES','Paquete');
define('DEF_PRODUCTO','Producto');
define('DEF_TIPOS','Tipos de productos');
define('DEF_MODELOS','Modelos de productos');
define('DEF_ATRIBUTOS','Atributos de productos');
define('DEF_PROVEEDORES','Proveedores');
define('DEF_OLT','Cabeceras');
define('DEF_PROVISIONES','Provisiones');
define('DEF_T_FIJO','Telefonía Fija');
define('DEF_T_MOVIL','Telefonía Móvil');

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
define('MNU_ITEM_11','Trabajos');

define('MNU_ITEM_12','Configuración');
define('MNU_ITEM_13','Facturación');
date_default_timezone_set('Europe/Madrid');

/** acceso servidor telefonia */
define ('DB_TELEFONIA_SERVER' , '89.140.16.198');
define ('DB_TELEFONIA_DATABASENAME', 'gestioncdr');
define ('DB_TELEFONIA_USER', 'testerp');
define ('DB_TELEFONIA_PASSWORD', 'erpDirect18');


define("ESPACIO","\040");


// interconexion Nueva plataforma -> antigua plataforma

define('RUTA_ANTIGUA','http://localhost/');
define("CLAVE_API","2a10c77db1d6d0bedc7eafe582041830");

/** ruben, id atributos y otros */

define('ATRIBUTO_TELEFONO_FIJO',45);
define('ATRIBUTO_TELEFONO_MOVIL',48);
define('ID_ATRIBUTO_TRONCAL',56);

define('NUMERO_LLAMAR_VERIFICAR_MOVIL','856001011');