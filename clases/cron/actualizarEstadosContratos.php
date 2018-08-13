<?php

require_once ('../Contrato.php');

/*
 * 1. Obtenemos los contratos con fechas de baja hoy.
 * 2. Seteamos las líneas de detalles a baja.
 * 3. Las demas líneas se ponen en alta.
 */
Contrato::getLineasContratoBajaHoy();



?>