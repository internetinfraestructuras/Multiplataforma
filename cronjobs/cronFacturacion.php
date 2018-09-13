<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 10/09/2018
 * Time: 13:50
 */

require_once ('../clases/Contrato.php');
require_once ('../clases/Factura.php');
require_once ('../clases/Empresa.php');
require_once ('../config/util.php');

//Hay que obtener todas las empresas para hacer sus correspondientes facturas de forma automática;


$listadoEmpresas=Empresa::getListadoEmpresas();

$fechaActual=date("Y-m-d");

for($k=0;$k<count($listadoEmpresas);$k++)
{
    $idEmpresa=$listadoEmpresas[$k][0];

    $diaFacturacion=Factura::getDiaFacturacion($idEmpresa);

    $automatica=Factura::getFacturacionAutomatica($idEmpresa);

    $diaFacturacion=$diaFacturacion[0][0];

    //Si el día de facturación no está configurado
    if($diaFacturacion=='')
        $diaFacturacion=5;



//Hay que hacer un prorrateo de los contraros de líneas para saber si estamos todo el mes y esta establecido la facturación automática
    if($diaFacturacion==date('d') && $automatica[0][0]==1)
    {

        $listaContratos=Contrato::getContratosAltaEmpresa($idEmpresa);

        for($i=0;$i<count($listaContratos);$i++)
        {
            $dto=0;
            $fechaAlta=$listaContratos[$i][3];

            $lineasContrato=Contrato::getLineasContratoAlta($listaContratos[$i][0]);

            $campanas=Contrato::getCampanasContrato($listaContratos[$i][0]);

            if(!empty($campanas))
            {
                for($l=0;$l<count($campanas);$l++)
                    $dto=$campanas[$l][1];

            }
            //OBTENEMOS LAS FACTURAS DEL MES EN CURSO DE DICHA EMPRESA,SI LA FACTURA YA ESTUVIESE GENERADA NO SE VUELVE A GENERAR!!!

           $facturasMes=Factura::getFacturasMesCurso($idEmpresa);
            $flag=false;

           for($o=0;$o<count($facturasMes);$o++)
           {
               if($facturasMes[$o][5]==$listaContratos[$i][0])
               {
                   $flag=true;
                   break;
               }
           }

           //SI EL FLAG ES FALSO QUIERE DECIR QUE NO EXISTE UNA FACTURA EN EL MES EN CURSO DE DICHO CONTRATO
            if($flag==false)
            {
                $idFactura=Factura::setNuevaFactura($listaContratos[$i][0],$_SESSION['REVENDEDOR'],$total,$impuesto,$descuento);
                $total=0;


                for($j=0;$j<count($lineasContrato);$j++)
                {

                    $idLinea=$lineasContrato[$j][0];
                    $impuesto=$lineasContrato[$j][5];
                    $importe=$lineasContrato[$j][6];

                    $total+=$importe;

                    $rs= Factura::setNuevaLineaFactura($idFactura,$idLinea,$importe,$impuesto);

                }



                $diasProrrateo = (strtotime($fechaActual)-strtotime($fechaAlta))/86400;
                $diaMes = cal_days_in_month(CAL_GREGORIAN, date('m'), date('y'));

                //Si los días de prorrateo es menor a los días del mes es el primer mes
                if($diasProrrateo<$diaMes)
                {
                    $totalN=$total-(($total/$diaMes)* ($diasProrrateo));
                    $total-=$totalN;
                }

                $totalBruto=($total*((100-$impuesto)/100));

                if($dto!=0)
                {
                    $totalDto=($total*($dto/100));
                    $totalDto=$total-$totalDto;

                }
                else
                    $totalDto=$total-$totalDto;





                echo "La línea de facturacion es: BRUTO $total con un $impuesto de IVA con dto:$dto. El total bruto es $totalBruto <br>";




                Factura::setImporteTotal($idFactura,$total,21,$dto,$totalDto);
            }

        }
    }
    else
    {
        echo "Hoy no se factura primo<br>";
    }

}


?>
