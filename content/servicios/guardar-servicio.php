<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 17/07/2018
 * Time: 11:50
 */
if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
require_once('../../config/def_tablas.php');
require_once ('../../clases/Servicio.php');

$util = new util();

check_session(3);
date_default_timezone_set('Etc/UTC');

error_reporting(E_ALL);
ini_set('display_errors', 0);


// todo: --------------------------------------------
// cuando el cliente es creado por primera vez
// todo: --------------------------------------------



if(isset($_POST['action']) && $_POST['action'] == 'servicios')
{

    $array = $required = array();

    // catch post data
    $post_data = isset($_POST['servicio']) ? $_POST['servicio'] : null;
    $is_ajax = (isset($_POST['is_ajax']) && $_POST['is_ajax'] == 'true') ? true : false;

    // check post data
    if ($post_data === null) {
        if ($is_ajax === false) {
            _redirect('#alert_mandatory');
        } else {
            die('_mandatory_');
        }
    }

    // EXTRACT DATA FROM POST
    foreach ($post_data as $key => $value)
    {
        $key_title = ucfirst($key);

        $explode = @explode('_', $key_title);
        if (!isset($explode[1]))
            $explode = @explode('-', $key_title);

        if (isset($explode[1])) {
            $key_title = implode(' ', $explode);
            $key_title = ucwords(strtolower($key_title));
        }

        // se recogen los datos post y se pasan por la funcion que limpia los caracteres suceptibles de generar inyeccion SQL

        $nombre = $util->cleanstring($post_data['nombre']);
        $tipo = $util->cleanstring($post_data['tipo']);
        $precioProv=$util->cleanstring($post_data['precio-proveedor']);
        $beneficio=$util->cleanstring($post_data['beneficio']);
        $pvp=$util->cleanstring($post_data['precio-pvp']);
        $impuesto=$util->cleanstring($post_data['impuesto']);
        $atributos=$post_data['atributo'];
        $proveedor=$post_data['proveedor'];

    }

    $values = array( $tipo,$_SESSION['REVENDEDOR'],$nombre,$precioProv,$impuesto,$beneficio,$pvp,$proveedor);

    // llama a la funcion insertInto de la clase util que recibe la tabla (string) y dos arrays (campos y valores)

    $resultServicio = $util->insertInto('servicios', $t_servicios, $values);


 //   $util->log('El administrador:'.$_SESSION['USER_ID'].' ha creado el servicio:'.$resultServicio.' con el resultado:'.$result);

    /*
     * UNA VEZ CREADO EL PRODUCTO ASOCIAMOS LOS ATRIBUTOS A DICHO PRODUCTO $RESULT TIENE EL ID DEL PRODUCTO
     */

    // EXTRACT DATA FROM POST

    $j=0;
    for($i=0;$i<count($atributos)/2;$i++)
    {

        $valor= $util->cleanstring($atributos[$j]);
        $j++;
        $id= $util->cleanstring($atributos[$j]);
        $j++;
        $values=array($id,$resultServicio,$valor);
        echo $valor."<br/>";

        $result = $util->insertInto('servicios_atributos', $t_servicios_atributos, $values);

    }


    if(intval($result)>0){
        if($is_ajax === false) {
            _redirect('#alert_success');
            exit;
        } else {
            die('_success_');
        }
    } else{
        if($is_ajax === false) {
            _redirect('#alert_failed');
            exit;
        } else {
            die('_failed_');
        }
    }

}

// todo: --------------------------------------------
// cuando el cliente es editado
// todo: --------------------------------------------

if(
    (isset($_POST['oper']) && $_POST['oper'] == 'edit')
    &&
    (isset($_POST['id']) && $_POST['id'] != '')
)
{


    $idServicio=$util->cleanstring($_POST['id']);
    @$nombre = $util->cleanstring($_POST['nombre']);
    $tipo = $util->cleanstring($_POST['tipo']);

    $servicio = $util->cleanstring($_POST['servicio']);

    $precioProv=$util->cleanstring($_POST['coste']);
    $beneficio=$util->cleanstring($_POST['beneficio']);
    $pvp=$util->cleanstring($_POST['pvp']);
    $impuesto=$util->cleanstring($_POST['impuesto']);
    $atributos=$_POST['atributo'];
    @$cascadaPrecio=$_POST['cascada-precio'];
    @$cascadaTecnico=$_POST['cascada-tecnico'];


    //Si el servicio proviende de un contrato
    if(isset($_POST['idContrato']))
    {
        //Llamada a la rutina compleja actualizar servicio de un contrato
        Servicio::actualizarServicioContrato($_POST['idContrato'],$_POST['idLinea'],$idServicio,$servicio,$precioProv,$beneficio,$pvp,$impuesto,$atributos);

    }
    else
    {

        for($i=0;$i<count($atributos);$i++)
        {

            $valor= $util->cleanstring($atributos["valor"][$i]);

            $id= $util->cleanstring($atributos["id"][$i]);

            $values=array($valor,$idProducto);
            // echo "Se modifica".$id." con el valor".$valor;
            $campos=array("valor");

            if(!isset($atributosNuevos))
            {


                $result = $util->update('servicios_atributos', $campos, $values, "servicios_atributos.id_servicio=" . $idServicio . " AND servicios_atributos.id=" . $id);
            }
            else

                $result=$util->delete('servicios_atributos','id',$id);

        }

        $values=array($nombre,$tipo,$precioProv,$impuesto,$beneficio,$pvp);
        $campos=array("nombre","id_servicio_tipo","precio_proveedor","impuesto","beneficio","pvp");
        $result = $util->update('servicios', $campos, $values, "id=".$idServicio);
        $util->log('El usuario:'.$_SESSION['USER_ID'].' ha modificado el servicio: '.$idServicio.' con el resultado:'.$result);


        if($cascadaPrecio=="on" || $cascadaTecnico=="on")
        {
            $campos=array("precio_proveedor","beneficio","impuesto","pvp");
            $values=array($precioProv,$beneficio,$impuesto,$pvp);

            $ls= buscarContratosConServicio($idServicio);

            for($i=0;$i<count($ls);$i++)
            {
                if($cascadaTecnico=="on")
                {

                    for($i=0;$i<count($atributos);$i++)
                    {
                        $valor= $util->cleanstring($atributos["valor"][$i]);

                        $id= $util->cleanstring($atributos["id"][$i]);

                        $values=array($valor,$idProducto);
                        // echo "Se modifica".$id." con el valor".$valor;
                        $campos=array("valor");

                        $result = $util->update('contratos_lineas_detalles', $campos, $values, "contratos_lineas.id=".$ls[$i]['id']);

                    }
                }


                if($cascadaPrecio=="on")
                    $result = $util->update('contratos_lineas', $campos, $values, "contratos_lineas.id=".$ls[$i]['id']);




                $values=array($ls[$i]['id_contrato'],$idServicio,$_POST['idLinea'],3);

                $resAnexo= $util->insertInto('contratos_anexos', $t_contratos_anexos, $values);

            }

        }
    }





} else{
    echo "nose";
    die();
}


function buscarContratosConServicio($idServicio)
{
    /*
     * SELECT *
FROM contratos,contratos_lineas
WHERE contratos.id=contratos_lineas.ID_CONTRATO
AND contratos.ID_EMPRESA=1 AND contratos_lineas.ID_TIPO=2 AND contratos_lineas.ID_ASOCIADO=24;
*/
    $util = new util();
    return $util->selectWhere3('contratos,contratos_lineas',
        array("contratos_lineas.id,contratos_lineas.id_contrato"),
        "contratos_lineas.estado=1 AND contratos.id=contratos_lineas.id_contrato AND contratos.id_empresa=".$_SESSION['REVENDEDOR']." AND contratos_lineas.id_tipo=2 AND contratos_lineas.id_asociado=".$idServicio);
}
function _redirect($hash) {

    $HTTP_REFERER = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

    if($HTTP_REFERER === null)
        die("Invalid Referer. Output Message: {$hash}");

    header("Location: {$HTTP_REFERER}{$hash}");
    exit;
}



?>