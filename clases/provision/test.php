<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 01/10/2018
 * Time: 8:50
 */


require_once ('Provision.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);
$p = new Provision();

print_r($p->bajaServicios('48575443CDE3319A',true));
