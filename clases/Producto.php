<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 19/09/2018
 * Time: 13:08
 */

class Producto
{

    public static function getProductosStock($idEmpresa)
    {
        $util = new util();
        $listado= $util->selectWhere3('productos,productos_tipos,productos_modelos,almacenes',
            array("productos.id",
                "productos.numero_serie",
                "productos_tipos.nombre as Tipo",
                "productos_modelos.nombre as Modelo"),
            "productos.id_tipo_producto=productos_tipos.id
                                                    AND productos.id_modelo_producto=productos_modelos.id 
                                                    AND almacenes.id=productos.id_almacen 
                                                    AND almacenes.id_empresa=$idEmpresa AND productos.estado=1");

    }
    public static function getProductosAsignados($idEmpresa)
    {
        $util = new util();
        $listado= $util->selectWhere3('productos,productos_tipos,productos_modelos,almacenes',
            array("productos.id",
                "productos.numero_serie",
                "productos_tipos.nombre as Tipo",
                "productos_modelos.nombre as Modelo"),
            "productos.id_tipo_producto=productos_tipos.id
                                                    AND productos.id_modelo_producto=productos_modelos.id 
                                                    AND almacenes.id=productos.id_almacen 
                                                    AND almacenes.id_empresa=$idEmpresa AND productos.estado=2");

    }
    public static function getProductosInstalados($idEmpresa)
    {
        $util = new util();
        $listado= $util->selectWhere3('productos,productos_tipos,productos_modelos,almacenes',
            array("productos.id",
                "productos.numero_serie",
                "productos_tipos.nombre as Tipo",
                "productos_modelos.nombre as Modelo"),
            "productos.id_tipo_producto=productos_tipos.id
                                                    AND productos.id_modelo_producto=productos_modelos.id 
                                                    AND almacenes.id=productos.id_almacen 
                                                    AND almacenes.id_empresa=$idEmpresa AND productos.estado=3");

    }

    public static function getProductosServicio($idEmpresa,$idTipoServicio)
    {
        $util = new util();
        return $util->selectWhere3('productos,productos_tipos,productos_modelos,almacenes',
            array("productos.id",
                "productos.numero_serie",
                "productos_tipos.nombre as Tipo",
                "productos_modelos.nombre as Modelo"),
            "productos.id_tipo_producto=productos_tipos.id
                                                    AND productos.id_modelo_producto=productos_modelos.id 
                                                    AND almacenes.id=productos.id_almacen 
                                                    AND almacenes.id_empresa=$idEmpresa AND productos.estado=1 AND productos_tipos.id_tipo_servicio=$idTipoServicio");

    }

}