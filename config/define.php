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

define('ID_PRODUCTO_STOCK',1);
define('ID_PRODUCTO_ASIGNADO',2);
define('ID_PRODUCTO_INSTALADO',3);
define('ID_PRODUCTO_RMA',4);
define('ID_PRODUCTO_RMA_BAJA',5);
define('ID_PRODUCTO_BAJA',6);

define('ID_ORDEN_PENDIENTE',1);
define('ID_ORDEN_TRAMITE',2);
define('ID_CERRADA',3);
define('ID_CANCELADA',4);

//DEVOLUCIONES API MAS MOVIL
define('LINEA_ACTIVA_MASMOVIL','A');
define('LINEA_BAJA_MASMOVIL','B');
define('LINEA_DESACTIVADA_MASMOVIL','D');
define('LINEA_SUSPENDIDA_MASMOVIL','S');
define('NO_EXISTE_CLIENTE_MASMOVIL','FIND-ERR-003');
define('OPERACION_OK_MASMOVIL','OK-001');


/*ID TRANSACCIONES API MAS MOVIL PARA EL LOG*/
define('ID_CAMBIO_TARIFA_MASMOVIL','1');
define('ID_ACTIVACION_ROAMING_MASMOVIL','2');
define('ID_DESACTIVAR_ROAMING_MASMOVIL','3');
define('ID_CAMBIO_ICC_MASMOVIL','4');
define('ID_BLOQUEO_LINEA_TEMPORAL','5');
define('ID_DESBLOQUEO_LINEA','6');


define('ID_ORDEN_INSTALACION',1);
define('ID_ORDEN_REPARACION',2);
define('ID_ORDEN_RMA',3);
define('ID_ORDEN_RMA_BAJA',4);
define('ID_ORDEN_BAJA',5);
define('ID_ORDEN_CANCELADA',6);


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
define('ATRIBUTO_TELEFONO_FIJO',45);
define('ATRIBUTO_TELEFONO_MOVIL',48);
define('NUMERO_LLAMAR_VERIFICAR_MOVIL','856001011');


define('ID_SERVICIO_INTERNET',1);
define('ID_SERVICIO_VOZIP',2);
define('ID_SERVICIO_MOVIL',3);
define('ID_SERVICIO_IPTV',4);

define('FIBRADB','fibra.');
define('NUEVADB','multiplataforma.');
define('ETIQUETAS','etiquetas.');

define('PROV_MASMOVIL',14);
define('PROV_AIRE',15);


//clientes documentos
define('CLI_DOC_DNI',1);
define('CLI_DOC_CIF',2);
define('CLI_DOC_RECIBO',3);
define('CLI_DOC_FACTURA_MOVIL',4);
define('CLI_DOC_FACTURA_FIJO',5);

define('CLI_RUTA_DOCUMENTOS','content/documentos/clientes/');


//------------------------------------------------------------------

/*atributos de SERVICIOS*/
define('ID_NUMERO_MOVIL',48);
define('ID_ATRIBUTO_ICC',48);
define('ID_ATRIBUTO_TRONCAL',56);
define('ID_ATRIBUTO_GRUPO_RECARGA',43);
define('ID_ATRIBUTO_PAQUETE_DESTINO',44);
define('ID_ATRIBUTO_BAJADA',40);
define('ID_ATRIBUTO_SUBIDA',39);


/*tipos de servicios*/
define('ID_SER_INTERNET',1);
define('ID_SER_FIJO',2);
define('ID_SER_MOVIL',3);
define('ID_SER_TV',4);


/*TIPOS DE ANEXOS*/
define('ID_ANEXO_ALTA',1);
define('ID_ANEXO_BAJA',2);
define('ID_ANEXO_CAMBIO_SERV',3);
define('ID_ANEXO_CAMBIO_PAQ',4);
define('ID_ANEXO_SOLICITUD_BAJA_SERV',5);
define('ID_ANEXO_CANCELACION_SOL_BAJA',6);
define('ID_ANEXO_BAJA_SERVICIO',7);
define('ID_ANEXO_ROTURA_PAQ',8);
define('ID_ANEXO_CAMBIO_PRECIO_PAQ',9);
define('ID_ANEXO_CAMBIO_TARIFA_PAQ',10);

//TIPOS DE ORDENES DE TRABAJO
define('ORDEN_INSTALACION',1);
define('REPARACIÓN',2);
define('RMA',3);
define('RMA_BAJA',4);
define('BAJA',5);
define('CANCELADA',6);


//DEVOLUCIONES API AIRENETWORKS
define('PETICION_AIRE_OK','0001');
define('PETICION_AIRE_USER_INVALIDO','0002');
define('PETICION_AIRE_PASS_INVALIDO','0003');
define('PETICION_AIRE_USER_NO_EXISTE','0004');
define('PETICION_AIRE_PASS_INCORRECTA','0005');
define('PETICION_AIRE_IP_INCORRECTA','0006');
define('PETICION_AIRE_PETICIONES_EXCEDIDAS','0100');
define('LINEA_AIRE_NO_PROCESADA','LINEA NO PROCESADA');
define('LINEA_AIRE_PORTABILIDAD_CANCELADA','PORTABILIDAD CANCELADA');
define('LINEA_AIRE_LINEA_ACTIVA','PORTABILIDAD CANCELADA');





/*ESTADOS DE CONTRATOS*/

define('CONTRATO_ALTA',1);
define('CONTRATO_BAJA',2);
define('CONTRATO_PROCESO_ALTA',3);
define('CONTRATO_PROCESO_BAJA',4);
define('CONTRATO_BAJA',5);
define('CONTRATO_PTE_CAMBIO',6);
define('CONTRATO_PROCESO_BAJA_CAMBIO',7);
define('CONTRATO_PROCESO_ALTA_CAMBIO',8);
define('CONTRATO_CANCELACION_CAMBIOS',9);
define('CONTRATO_IMPAGO',10);




define('DEF_MODULO_AIRE','Módulo AireNetworks');