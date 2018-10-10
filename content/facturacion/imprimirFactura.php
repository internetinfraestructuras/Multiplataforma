<?php
//error_reporting(E_ALL);
ini_set('display_errors', 0);
require_once('../../pdf/utilPDF.php');
require_once('../../pdf/tcpdf/tcpdf.php');



if (!isset($_SESSION)) {@session_start();}
$util=new utilPDF();

$idFactura=$_GET['idFactura'];


$cabecera=$util->selectWhere3("facturas,contratos,municipios,provincias,comunidades,clientes",
    array('facturas.numero,facturas.fecha,facturas.importe_bruto,facturas.total,facturas.impuesto,facturas.descuento,clientes.nombre,clientes.apellidos,clientes.dni,clientes.direccion,municipios.municipio,provincias.provincia,contratos.id'),
    "facturas.id=$idFactura AND
            facturas.id_empresa=".$_SESSION['REVENDEDOR']." AND 
             municipios.id=clientes.localidad AND
            facturas.id_contrato=contratos.id AND
            contratos.id_cliente=clientes.id 
            AND provincias.id=clientes.provincia 
            AND comunidades.id=clientes.comunidad");

$numeroFactura=$cabecera[0][0];
$fecha=$cabecera[0][1];
$importeBruto=$cabecera[0][2];
$total=$cabecera[0][3];
$impuesto=$cabecera[0][4];
$dto=$cabecera[0][5];
$nombre=$cabecera[0][6];
$apellidos=$cabecera[0][7];
$dni=$cabecera[0][8];
$direccion=$cabecera[0][9];
$municipio=$cabecera[0][10];
$provincia=$cabecera[0][11];
$idContrato=$cabecera[0][12];

$lineas=$util->selectWhere3("facturas,facturas_lineas,contratos_lineas",
    array('contratos_lineas.id_tipo,contratos_lineas.id_asociado,contratos_lineas.precio_proveedor,contratos_lineas.beneficio,contratos_lineas.impuesto,contratos_lineas.pvp'),
    "facturas.id=facturas_lineas.id_factura AND facturas_lineas.id_linea_contrato=contratos_lineas.id");
$content="<br><br><br><hr><table border=\"1\" style=\"text-align: center;padding:5px;\">
<tr style=\"background-color: #9e9e9e\"><th>CANTIDAD</th><th>CONCEPTO</th><th>IMPORTE UNITARIO</th><TH>Impuestos</TH><th>IMPORTE</th></tr>";

$campanas=$util->selectWhere3("contratos_campanas,campanas",array("campanas.nombre,contratos_campanas.dto,contratos_campanas.dto_hasta"),
    "contratos_campanas.id_campana=campanas.id 
            AND contratos_campanas.id_contrato=$idContrato");



for($i=0;$i<count($lineas);$i++)
{
    $tipoLinea=$lineas[$i][0];
    $asociado=$lineas[$i][1];
    $precioProv=$lineas[$i][2];
    $beneficio=$lineas[$i][3];
    $impuesto=$lineas[$i][4];
    $pvp=$lineas[$i][5];

   if($tipoLinea==1)
    {
        $concepto=$util->selectWhere3("paquetes",array("nombre"),"paquetes.id=$asociado");
        $concepto=$concepto[0][0];
    }
    else if($tipoLinea==2)
    {
        $concepto=$util->selectWhere3("servicios",array("nombre"),"servicios.id=$asociado");
        $concepto=$concepto[0][0];
    }

$content.="<tr><td>1</td><td>$concepto</td><td>$precioProv  &euro;</td><td>$impuesto %</td><td>$precioProv  &euro;</td></tr>";

}
$content.="<tr><td colspan=\"4\" style=\"text-align:right;\"a>Subtotal:</td><td>$total &euro;</td></tr>";

if($campanas[0]!=NULL)
{
    $nombreCampana=$campanas[0][0];
    $dto=$campanas[0][1];
    $hasta=$campanas[0][2];

    $content.="<tr><td colspan=\"4\" style=\"text-align:right;\"a><strong>Descuentos:</strong></td><td>$dto %</td></tr>";
    $contentCampanas="Este contrato tiene aplicada una campaña de promoción '$nombreCampana', con duración hasta $hasta .";
}
$content.="<tr><td colspan=\"4\" style=\"text-align:right;\"a><strong>Total:</strong></td><td>$total &euro;</td></tr>";
$content.="</table>";

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);

$pdf->SetAuthor('REVENDEDOR');

$pdf->SetTitle('FACTURA CLIENTE');
$pdf->SetSubject('FACTURA');
$pdf->SetKeywords('FACTURA');



$pdf->SetHeaderData($file="../../img/antena.png","10px", " FACTURA#".$numeroFactura, array(0,64,255), array(0,64,128));


$pdf->setFooterData(array(0,64,0), array(0,64,128));
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->AddPage();



$datosCliente = '<strong>Factura#:'.$numeroFactura.'<br></strong><strong>Fecha:</strong>'. date("d/m/Y",$fecha).'<br><br>DATOS DE LA EMPRESA<BR>
C/ Pruebas 123<br>
11400 Jerez de la Frontera<br>
España<br>
CIF<br>';
$pdf->MultiCell(0, 0, $datosCliente, 0, 'J', false, 1, 10, 11, true, 0, true, true, 0, 'T', false);


$datosCliente = '<B>DATOS CLIENTE:</B><br>
                <b>Nombre:</b>'.$nombre." ".$apellidos."<br/>
                <b>Dirección:</b>".$direccion."<br/>"
."<b>Localidad:</b>$municipio<br/>
                <b>Provincia:</b>$provincia<br/>";
$pdf->MultiCell(70, 50, $datosCliente, 0, 'J', false, 1, 100, 11, true, 0, true, FALSE, 0, 'T', false);


$pdf->writeHTML($content);
$pdf->writeHTML($contentServiciosContratados);

$pdf->writeHTML($contentCampanas);

$pdf->output('Reporte.pdf', 'I');

?>
