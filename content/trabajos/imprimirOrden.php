<?php

error_reporting('0');
ini_set('display_errors', '0');

include_once('../../pdf/utilPDF.php');

require_once('../../pdf/tcpdf/tcpdf.php');
if (!isset($_SESSION)) {@session_start();}
$util=new utilPDF();


$listado= $util->selectWhere3('ordenes,ordenes_estados,contratos,clientes,provincias,municipios',
    array("ordenes.id,ordenes.fecha_alta,ordenes_estados.nombre,ordenes_estados.id,clientes.nombre,clientes.id,clientes.apellidos,clientes.direccion,provincias.provincia,municipios.municipio,clientes.fijo,clientes.movil"),
    "ordenes.id_contrato=contratos.id 
                                            AND ordenes_estados.id=ordenes.id_tipo_estado 
                                            AND contratos.id_cliente=clientes.id
                                            AND provincias.id=clientes.provincia
                                            AND municipios.id=clientes.localidad
                                            AND contratos.id_empresa=".$_SESSION['REVENDEDOR']);

$idOrden=$listado[0][0];
$fecha=$listado[0][1];
$estado=$listado[0][2];
$cliente=$listado[0][4];
$apellidos=$listado[0][6];
$direccion=$listado[0][7];
$provincia=$listado[0][8];

$localidad=$listado[0][9];
$fijo=$listado[0][10];
$movil=$listado[0][11];

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);

$pdf->SetAuthor('REVENDEDOR');

$pdf->SetTitle('ORDEN DE TRABAJO');
$pdf->SetSubject('FACTURA MES CURSO');
$pdf->SetKeywords('FACTURA CLIENTE DEMO');
$pdf->SetHeaderData("","","", " ORDEN DE TRABAJO #".$idOrden, array(0,64,255), array(0,64,128));

$pdf->setFooterData(array(0,64,0), array(0,64,128));
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->AddPage();
$content = '';
$reporte="PRUEBAS";

//CABECERA DEL LISTADO
$pdf->MultiCell(70, 50, $txt, 0, 'J', false, 1, 125, 30, true, 0, false, true, 0, 'T', false);
$datosCliente = '<b>#ORDEN:</b>'.$idOrden."<br/><b>FECHA:</b>".$fecha;
$pdf->MultiCell(70, 50, $datosCliente, 0, 'J', false, 1, 10, 30, true, 0, true, true, 0, 'T', false);


$datosCliente = '<B>DATOS CLIENTE:</B><br>
                <b>Nombre:</b>'.$cliente." ".$apellidos."<br/>
                <b>Dirección:</b>".$direccion."<br/>"
    ."<b>Localidad:</b>$localidad<br/>
                <b>Provincia:</b>$provincia<br/>
                <b>Teléfono:</b>$fijo<br/>";
$pdf->MultiCell(70, 50, $datosCliente, 0, 'J', false, 1, 120, 30, true, 0, true, FALSE, 0, 'T', false);




$tabla="<table cellspacing=\"0\" cellpadding=\"1\" border=\"1\" style=\"text-align: center;\">
                        <tr>
                        <th width=\"20px\">#</th><th>TIPO ORDEN</th><th>TIPO SERVICIO</th><th  width=\"80px\">MODELO</th><th  width=\"180px\">NÚM.SERIE</th><th></th>
                        </tr>";

$lineas= $util->selectWhere3('ordenes,ordenes_lineas,contratos_lineas_detalles,ordenes_tipos,contratos_lineas,contratos_lineas_tipo,contratos,servicios_tipos',
    array("ordenes_lineas.id","ordenes_tipos.nombre","ordenes_lineas.id_producto","contratos_lineas_tipo.nombre","contratos_lineas.id_tipo","contratos_lineas.id_asociado","servicios_tipos.nombre"),
    "ordenes.id=ordenes_lineas.id_orden AND contratos_lineas_tipo.id=contratos_lineas.id_tipo AND contratos_lineas.id=contratos_lineas_detalles.id_linea
    AND ordenes_tipos.id=ordenes_lineas.id_tipo_orden
    AND ordenes.id_contrato=contratos.id
    AND servicios_tipos.id=contratos_lineas_detalles.id_tipo_servicio
    AND contratos_lineas_detalles.id=ordenes_lineas.id_linea_detalle_contrato AND ordenes_lineas.id_orden=".$_GET['idOrden'].' AND contratos.id_empresa='.$_SESSION['REVENDEDOR']);

$total=0;

for($i=0;$i<count($lineas);$i++)
{
    $id=$lineas[$i][0];
    $tipoOrden=$lineas[$i][1];
    $idProducto=$lineas[$i][2];

    $servicioTipo=$lineas[$i][3];
    $idTipo=$lineas[$i][4];
    $idAsociado=$lineas[$i][5];
    $producto="No hay producto asociado";
    $nombreServicio=$lineas[$i][6];
    if($idProducto!=0)
    {
        $productos=$util->selectWhere3("productos,productos_tipos,productos_modelos",
            array("productos.ID","productos.numero_serie","productos_tipos.nombre","productos_modelos.nombre"),
            "productos.id_tipo_producto=productos_tipos.id AND productos.id_modelo_producto=productos_modelos.id AND productos.id=".$idProducto." ");

        $producto=$productos[0][1];
        $tipoProducto=$productos[0][2];
        $modelo=$productos[0][3];
    }


    if($idEstado!=3)
        $totalContrato+=$pvp;

    $tipo=strtolower($servicioTipo);
    if($idTipo!=3)
        $listado= $util->selectWhere3($tipo, array("ID","nombre"),  $tipo.".id_empresa=".$_SESSION['REVENDEDOR']." AND ".$tipo.".id=".$idAsociado);
    else
        $listado= $util->selectWhere3($tipo.",almacenes", array("productos.ID","numero_serie"),  "almacenes.id=productos.id_almacen AND almacenes.id_empresa=".$_SESSION['REVENDEDOR']." AND ".$tipo.".id=".$idAsociado);

    $idServicioTipo=$listado[0][2];

    $nombre=$listado[0][1];

    $tabla.="<tr>
                <td>$id</td><td>$tipoOrden</td><td>$nombreServicio</td><td>$modelo</td><td>$producto</td><td>$nombre</td>
                </tr>";

}

$tabla.="</table>";

$pdf->writeHTML($tabla);
$content="En _____________________ a ____/_____/_____. <br><br><br><br><br><br><br><br><br>Firma $cliente $apellidos.";
$pdf->writeHTML($content);


$pdf->output('Reporte.pdf', 'I');
?>
