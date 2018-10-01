<?php
/**
 * Created by PhpStorm.
 * User: telefonia
 * Date: 23/07/2018
 * Time: 10:07
 */

require_once('classTelefonia.php');
//echo "test\n";
$tel = new Telefonia();

$cifSuperUsuario='B45782687';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


//PRUEBAS SUPERUSUARIOS

//get saldo de un reventa

/*
try {

    $res = $tel->getSaldo('X7966192G');
    echo "saldo $res";
}
catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}*/

//get saldo cliente Y3850151B
/*
try {

    $res = $tel->getSaldoCliente('Y3850151B');
    echo "saldo $res";
}
catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}*/

//recargar saldo a un cliente
//recargarSaldoCliente($cifCliente,$importeRecarga)
/*
try {

    $res = $tel->recargarSaldoCliente('Y3850151B',20);
    if ($res == 1)
        echo "ok recargado";
    else
        echo "error recargando";
}
catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}
*/



//get tarifas redistribucion de un superusuario
/*
try {

    $res = $tel->getTarifasRedistribucion($cifSuperUsuario);

    while ($row = mysqli_fetch_array($res)) {
        echo "grupo:".$row[0]."<br>";
        echo "descripcion:".$row[1]."<br>";
        echo "prefijo:".$row[2]."<br>";
        echo "coste:".$row[3]."<br>";
        echo "<br><br>";
    }
}
catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}*/


/** PRUEBAS PAQUETES DE DESTINOS */

//test crear un paquete de destinos
/*
try {
    $res = $tel->addPaqueteDestino($cifSuperUsuario, 'Paquete España Fijos y Moviles');
    if ($res == 1)
        echo "ok insertado";
    else
        echo "error";
}
catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}
*/

//test leer paquetes de destinos
/*
$packs = $tel->getPaquetesDestino($cifSuperUsuario);
while ($row = mysqli_fetch_array($packs)) {
    echo "ID:".$row[0]."<br>";
    echo "Nombrepack:".$row[1]."<br>";
    echo "<br>";
}*/

//modificar el nombre de un paquete de destinos
/*
try {
    $res = $tel->updatePaqueteDestino(1,'Pack Yea');
    if ($res == 1)
        echo "ok modificado";
    else
        echo "error";
}
catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}*/

//eliminar un paquete de destinos
/*
try {
    $res = $tel->deletePaqueteDestino(1);
    if ($res == 1)
        echo "ok eliminado";
    else
        echo "error";
}
catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}
*/

//añadir un destino o tarifa a un paquete de destinos
/*
try {
    $res = $tel->addPaqueteDestinoTarifa(2,'Alemania-VodafoneMoviles','Alemania - Vodafone Moviles','0049142',0.110407);
    if ($res == 1)
        echo "ok tarifa o destino insertada";
    else
        echo "error insertando destino";
}
catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}*/

//eliminar un destino o tarifa de un paquete de destinos
/*
try {
    $res = $tel->deletePaqueteDestinoTarifa(2,'0049150');
    if ($res == 1)
        echo "ok tarifa o destino eliminada";
    else
        echo "error eliminando destino";
}
catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}
*/


//asignar todas las tarifas de un paquete de destinos a la troncal de un cliente

/*
try {
    //$troncal='zzxBiope19QWb';
    $res = $tel->setTarifasTroncalFromPaqueteDestinos('bSWTtgtS9EUioEZTowPL',2,50);
    if ($res == 1)
        echo "tarifas añadidas a la troncal";
    else
        echo "error";
}
catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}
*/


///TEST NUMEROS DISPONIBLES PARA SELECCIONAR
///
/// //GET PROVINCIAS CON NUMERICOS
/*
try {

    $res = $tel->getProvinciasNumericosDisponibles();

    while ($row = mysqli_fetch_array($res)) {
        echo "id prov:".$row[0]."<br>";
        echo "prov:".$row[1]."<br>";
        echo "<br><br>";
    }
}
catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}*/

//leer los numeros libres de un provincia
/*try {

    $res = $tel->getNumericosDisponiblesFromProvincia(1);

    while ($row = mysqli_fetch_array($res)) {
        echo "numero libre:".$row[0]."<br>";
        echo "<br><br>";
    }
}
catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}
*/
//establecer un numero como asignado
/*try {

    $res = $tel->setNumericoDisponibleEstado('954000000','ASIGNADO');

    if ($res == 1)
        echo "estado actualizado";
    else
        echo "error";
}
catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}*/


//TEST PARA NUMERICOS DISPONIBLES POR PORTABILIDAD----------------------------------------
/*
try {

    $res = $tel->getNumericosPortas();

    while ($row = mysqli_fetch_array($res)) {
        echo "id prov:".$row[0]."<br>";
        echo "prov:".$row[1]."<br>";
        echo "<br><br>";
    }
}
catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}*/

//leer los numeros libres de un provincia
/*try {

    $res = $tel->getNumericosDisponiblesFromProvincia(1);

    while ($row = mysqli_fetch_array($res)) {
        echo "numero libre:".$row[0]."<br>";
        echo "<br><br>";
    }
}
catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}
*/
//establecer un numero como asignado
/*try {

    $res = $tel->setNumericoDisponibleEstado('954000000','ASIGNADO');

    if ($res == 1)
        echo "estado actualizado";
    else
        echo "error";
}
catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}*/



//TEST LEER GRUPOS DE RECARGA
/*
$grupos = $tel->getGruposRecarga($cifSuperUsuario);
while ($row = mysqli_fetch_array($grupos)) {
    echo "grupo:".$row[0]."<br>";
}
*/


//TEST inserto un grupo de recarga
/*try {
    $res = $tel->addGrupoRecarga($cifSuperUsuario, 'grupo6', 50, 'NO');
    if ($res == 1)
        echo "ok insertado";
}
catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}*/

//TEST BORRAR GRUPO RECARGA
/*
try {
    $res = $tel->deleteGrupoRecarga($cifSuperUsuario,'Mi nuevo grupo');
    if ($res == 1)
        echo "ok eliminado";
    else
        echo "error";
}
catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}
*/

//TEST INSERTAR UN USUARIO
/*
try{

    $cifCliente='48000000R';
    $nombreCliente="Pepe Tester Yea";
    $direccion="Calle Testing 123";
    $email="test@pepe.com";
    $tipoFacturacion="MPRE";
    $nombreGrupoRecarga="grupo5";

    $res = $tel->addCliente($cifSuperUsuario,$cifCliente,$nombreCliente,$direccion,$email,
        $umbralAlerta=5,$noficado='no',$tipoFacturacion,$nombreGrupoRecarga);
}
catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}*/

//TEST AÑADIR UNA LINEA A DICHO USUARIO
/*
$cifCliente='48000000R';
$numero="956000956";
try {
    $res=$tel->addLinea($cifCliente, "","",$numero);
    if($res!="")
        echo "ALTA OK";
    else
        echo "Error insertando la linea";

}catch (Exception $e) {
        echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}*/

//eliminar las tarifas de una troncal
//deleteTarifasTroncal($troncal)
/*try {
    $troncal='zzxBiope19QWb';
    $res=$tel->deleteTarifasTroncal($troncal);
    if($res!="")
        echo "BORRADO OK OK";
    else
        echo "ERROR BORRANDO la linea";

}catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}*/

//actualizar las tarifas de una troncal

/*
try {
    $troncal='zzxBiope19QWb';
    $res=$tel->updateTarifasTroncalFromPaqueteDestinos($troncal,2,50);
    if($res!="")
        echo "ACTUALIZADO OK";
    else
        echo "ERROR ACTUALIZANDO DESTINOS";

}catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}
*/

//* public function getPaqueteID($cifSuperUsuario,$nombre){*/
/*
try {
    $res=$tel->getPaqueteID('B45782687','Paquete España Fijos y Moviles');
    if($res!="")
        echo "ACTUALIZADO OK $res";
    else
        echo "ERROR ACTUALIZANDO DESTINOS $res";

}catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}
*/

//pruebas desactivar una linea
//desactivarLinea($troncal)

/*
try {
    $troncal='zxPgq05bmRc';
    $res=$tel->desactivarLinea($troncal);
    if($res!="")
        echo "desactivada con exito $res";
    else
        echo "ERROR desactivando linea $res";

}catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}*/


//
//updateLineaFromONT($numeroPonAntiguo,$numeroPonNuevo){

/*
try {
    $res=$tel->updateLineaFromONT("X4420078F","zxPgq05bmRc","48575443CBEC5EXX");
    if($res!="")
        echo "pon updateada con exito";
    else
        echo "ERROR updateando ont";

}catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}*/

//add linea from ont
try {
    $res=$tel->addLineaFromONT("X4420078F","48575443606A9F9A","956444444");
    if($res!="")
        echo "añadido con exito";
    else
        echo "ERROR añadiendo";

}catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}