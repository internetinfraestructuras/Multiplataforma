<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 13/09/2018
 * Time: 8:53
 * REVISA TODOS LOS CONTRATOS EN EL SISTEMA Y COMPRUEBA LAS FECHAS DE BAJA DE LOS CONTRATOS, SI ES A DÍA DE HOY SE LE DA DE BAJA A TODOS SUS DATOS Y DETALLES
 */

require_once ('../clases/Contrato.php');
require_once ('../clases/Factura.php');
require_once ('../clases/Empresa.php');
require_once ('../config/util.php');

Contrato::getContratosImportes(1,"20190422");








?>
