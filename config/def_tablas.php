<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 14/02/2018
 * Time: 12:49
 */

// definimos los arrays que contendran los campos de las distintas tablas

$t_usuarios=array('dni','nombre','apellidos','email','telefono','nivel','clave','notas','fecha_alta','ultima_modificacion','ultimo_acceso','activo','hash');

$t_revendedores=array('hash','dni','nombre','apellidos','empresa','direccion','localidad','provincia','region', 'cp','tel1','tel2','email','web','logo','notas');
$t_proveedores=array('nombre',"id_empresa");
$t_clientes=array('dni','nombre','apellidos','direccion','localidad','provincia','region', 'cp','tel1','tel2','email','notas','fecha_alta','fecha_modificacion','user_create');

$t_logs=array('log','ip','tipo');

$t_mod_plataforma=array('nombre_modulo','descripcion','icono','ruta','posicion');

$t_iconos=array('ruta','max_width');

$t_equipos=array('nserie','mac','ip4','ip6','usuario','clave','marca','modelo','firm');

$t_cabeceras=array('id','marca','modelo','ip','usuario','clave','descripcion','localidad','provincia','comunidad','wifero','chasis','tarjeta','pon','id_inicial');

$t_aprovisionados=array('id_cliente','id_usuario','fecha','lat','lon','internet','iptv','voip','modelo_ont',
    'gestionada_por','tipo_ip','asignada_por','descripcion','velocidad_up','velocidad_dw','ppoe_usuario',
    'ppoe_password','id_en_olt','c','t','p','serial','caja','puerto','id_internet','id_voip','id_iptv', 'cabecera','num_pon','id_vpn','pppoe_profile','id_acs');

$t_olts=array('marca','modelo','ip','usuario','clave','descripcion','localidad','provincia','comunidad','wifero','chasis','tarjeta','pon','id_inicial');

