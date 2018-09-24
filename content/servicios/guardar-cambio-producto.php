<?php

if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
require_once('../../config/def_tablas.php');
require_once ('../../clases/Contrato.php');
require_once ('../../clases/Servicio.php');
require_once ('../../clases/Orden.php');
require_once ('../../clases/masmovil/MasMovilAPI.php');
$util = new util();

check_session(3);

$idServicio=$util->cleanstring($_POST['servicio']);
$idProductoNuevo=$util->cleanstring($_POST['idProducto']);
$idProductoOriginal=$util->cleanstring($_POST['idProductoOriginal']);
$motivo=$util->cleanstring($_POST['motivo']);
$tipo=$util->cleanstring($_POST['tipo']);
$contrato=$util->cleanstring($_POST['contrato']);

$idProveedor=Servicio::getProveedor($idServicio);


/*SI EL CAMBIO ES DE INTERNET SE ESTABLECE EL PRODUCTO ACTUAL EN RMA Y SE CREA LA ORDEN DE CAMBIO*/
echo "El contrato es $contrato";

if($tipo==1)
{
    echo "El cambio es de INTERNET";

    $idLinea=Contrato::getLineaProducto($idProductoOriginal);
    $idLinea=$idLinea[0][0];

    Contrato::setProductoBaja($idProductoOriginal,ID_PRODUCTO_RMA);
    Contrato::setNuevoProductoContrato($idLinea,$idProductoNuevo,ID_PRODUCTO_ASIGNADO);

    $idOrden=Orden::crearOrdenTrabajo($contrato,"");

    //linea de retirada de producto
    Orden::crearLineaOrden($idOrden,ID_ORDEN_RMA,$idProductoOriginal,$idLinea);

    //linea de instalación de producto
    Orden::crearLineaOrden($idOrden,ID_ORDEN_INSTALACION,$idProductoNuevo,$idLinea);

    Contrato::setProductoEstado($_SESSION['REVENDEDOR'],$idProductoOriginal,ID_PRODUCTO_RMA);

    Contrato::setProductoEstado($_SESSION['REVENDEDOR'],$idProductoNuevo,ID_PRODUCTO_ASIGNADO);

}
else if($tipo==2)
{
echo "CAmbio de producto de telefonía fija";
}
else if($tipo==3)
{
    if($idProveedor[0][0]==ID_PROVEEDOR_MASMOVIL)
    {
        echo "El cambio es de INTERNET";

        $idLinea=Contrato::getLineaProducto($idProductoOriginal);
        $idLinea=$idLinea[0][0];

        Contrato::setProductoBaja($idProductoOriginal,ID_PRODUCTO_RMA);
        Contrato::setNuevoProductoContrato($idLinea,$idProductoNuevo,ID_PRODUCTO_ASIGNADO);

        $idOrden=Orden::crearOrdenTrabajo($contrato,"");

        //linea de retirada de producto
        Orden::crearLineaOrden($idOrden,ID_ORDEN_RMA,$idProductoOriginal,$idLinea);

        //linea de instalación de producto
        Orden::crearLineaOrden($idOrden,ID_ORDEN_INSTALACION,$idProductoNuevo,$idLinea);

        Contrato::setProductoEstado($_SESSION['REVENDEDOR'],$idProductoOriginal,ID_PRODUCTO_BAJA);

        Contrato::setProductoEstado($_SESSION['REVENDEDOR'],$idProductoNuevo,ID_PRODUCTO_INSTALADO);

        $apiMasMovil=new MasMovilAPI();

    $numero="691934413";
        $resultado=$apiMasMovil->getListadoClientes("",$numero);
        $refClienteAPI=$resultado->Client[0]->refCustomerId;

       $resTrans= $apiMasMovil->getEstadoCambioIccid($refClienteAPI,$numero,"1231231","");
       var_dump($resTrans);
       $apiMasMovil->setLogApi($numero,$resTrans,$_SESSION['REVENDEDOR'],1);

    }
    else if($idProveedor[0][0]==ID_PROVEEDOR_AIRENETWORKS)
    {
        $idLinea=Contrato::getLineaProducto($idProductoOriginal);
        $idLinea=$idLinea[0][0];
        Contrato::setProductoBaja($idProductoOriginal,6);
        Contrato::setNuevoProductoContrato($idLinea,$idProductoNuevo,"2");
      //  Contrato::setProductoInstalado($idProductoOriginal,$idLinea);

    }
}
else if($tipo==4)
{
echo "Cambios de producto de TV";
}







?>