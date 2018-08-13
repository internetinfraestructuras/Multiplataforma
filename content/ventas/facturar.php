<?php
require_once ('../../config/util.php');

require_once('../../tcpdf/tcpdf.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'ISO-8859-1', false);

$util=new util();


$lineas= $util->selectWhere3('contratos_lineas,contratos_lineas_tipo,contratos,estados_contratos',
    array("contratos_lineas.id","contratos_lineas_tipo.nombre as Tipo","contratos_lineas_tipo.id","contratos_lineas.id_asociado","contratos_lineas.pvp","estados_contratos.nombre as nombrecontrato","estados_contratos.id as idestado","contratos_lineas.fecha_alta","contratos_lineas.fecha_baja"),
    "contratos.id=contratos_lineas.id_contrato 
            and contratos_lineas_tipo.id=contratos_lineas.id_tipo
            AND contratos.id_empresa=".$_SESSION['REVENDEDOR']." AND contratos.id=".$_GET['idContrato']." AND contratos_lineas.estado!=2 AND estados_contratos.id=contratos_lineas.estado");



$totalContrato=0;

for($i=0;$i<count($lineas);$i++)
{
    $id=$lineas[$i][0];
    $tipo=$lineas[$i][1];
    $idTipo=$lineas[$i][2];
    $idAsociado=$lineas[$i][3];
    $pvp=$lineas[$i][4];
    $estado=$lineas[$i][5];
    $idEstado=$lineas[$i][6];
    $alta=$lineas[$i][7];
    $baja=$lineas[$i][8];

    if($idEstado!=3)
        $totalContrato+=$pvp;




    $tipo=strtolower($tipo);

    if($idTipo!=3)
        $listado= $util->selectWhere3($tipo, array("ID","nombre","id_servicio_tipo"),  $tipo.".id_empresa=".$_SESSION['REVENDEDOR']." AND ".$tipo.".id=".$idAsociado);
    else
        $listado= $util->selectWhere3($tipo.",almacenes", array("productos.ID","numero_serie"),  "almacenes.id=productos.id_almacen AND almacenes.id_empresa=".$_SESSION['REVENDEDOR']." AND ".$tipo.".id=".$idAsociado);

    $idServicioTipo=$listado[0][2];

    $nombre=$listado[0][1];



}




echo "WOK";
$pdf->Output('exss.pdf', 'D');
?>