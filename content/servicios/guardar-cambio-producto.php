<?php

if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
require_once('../../config/def_tablas.php');
require_once ('../../clases/Contrato.php');
require_once ('../../clases/Servicio.php');
require_once ('../../clases/Producto.php');
require_once ('../../clases/Orden.php');

$util = new util();

check_session(3);
var_dump($_POST);
$idServicio=$util->cleanstring($_POST['servicio']);
$idProductoNuevo=$util->cleanstring($_POST['idProducto']);
$idProductoOriginal=$util->cleanstring($_POST['idProductoOriginal']);

$tipo=$util->cleanstring($_POST['tipo']);
$contrato=$util->cleanstring($_POST['contrato']);

$idProveedor=Servicio::getProveedor($idServicio);


/*SI EL CAMBIO ES DE INTERNET SE ESTABLECE EL PRODUCTO ACTUAL EN RMA Y SE CREA LA ORDEN DE CAMBIO*/
echo "El contrato es $contrato";

if($tipo==ID_SER_INTERNET)
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
else if($tipo==ID_SER_FIJO)
{
    echo "El cambio es de FIJO";

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
else if($tipo==ID_SER_MOVIL)
{

    $numeroMovil=$util->cleanstring($_POST['numeroMovil']);
    $motivo=$util->cleanstring($_POST['motivo']);

    if($idProveedor[0][0]==ID_PROVEEDOR_MASMOVIL)
    {
        require_once ('../../clases/masmovil/MasMovilAPI.php');


        $rsP=Producto::getNumeroSerieProducto($_SESSION['REVENDEDOR'],$idProductoNuevo);
        $numeroSerie=$rsP[0][0];


        $apiMasMovil=new MasMovilAPI();
        $resultado=$apiMasMovil->getListadoClientes("",$numeroMovil);
        $refClienteAPI=$resultado->Client[0]->refCustomerId;


        $resTrans= $apiMasMovil->peticionCambioIccparaMsisdn($refClienteAPI,$numeroMovil,$numeroSerie,$motivo);

        $apiMasMovil->setLogApi($numeroMovil,$resTrans,$_SESSION['REVENDEDOR'],1);

    }
    else if($idProveedor[0][0]==ID_PROVEEDOR_AIRENETWORKS)
    {
        require_once ('../../clases/airenetwork/sim.php');
        //CAMBIO TECNICO EN LA API DE AIRE

    }


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
}
else if($tipo==ID_SER_TV)
{
    echo "El cambio es de TELEVISION";

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







?>