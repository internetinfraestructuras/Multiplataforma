<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 01/10/2018
 * Time: 8:50
 */


require_once ('Provision.php');

$p = new Provision();

print_r($p->listarVelocidades('48575443CDE3319A'));
