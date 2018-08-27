<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 09/08/2018
 * Time: 9:04
 */
/*
     ╔═══════════════════════════════════════════════════════════════════════╗
     ║ Devuelve un listado de numeros fijo libres en la api        ║
     ║ correspondiente dependiendo del parametro pasado            ║
     ║ como nombre del proveedor,
     ╚═══════════════════════════════════════════════════════════════════════╝
*/

if (!isset($_SESSION)) {
    @session_start();
}


require_once('../../clases/telefonia/classTelefonia.php');
//echo "test\n";
$tel = new Telefonia();
$aItems = array();


try {

    $res = $tel->getProvinciasNumericosDisponibles();

    while ($row = mysqli_fetch_array($res)) {
        $aItem = array(
            'provincia' => $row[0],
            'num' => $row[1],
            'selectable'=>false
        );
        $prov=$row[0];
        array_push($aItems, $aItem);

        try {

            $res1 = $tel->getNumericosDisponiblesFromProvincia(1);

            while ($row1 = mysqli_fetch_array($res1)) {

                $aItem = array(
                    'provincia' => $prov,
                    'num' => $row1[0],
                    'selectable'=>true
                );
                array_push($aItems, $aItem);

            }
        }
        catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
        }

    }
}
catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}


header('Content-type: application/json; charset=utf-8');
echo json_encode($aItems);