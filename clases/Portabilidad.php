<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 24/08/2018
 * Time: 9:36
 */

class Portabilidad
{

    public static function getDatosPortabilidadPDF($idPortabilidad)
    {
        /*
         * SELECT portabilidades.NOMBRE_TITULAR,
	portabilidades.CIF_TITULAR,
    portabilidades.DIR_TITULAR,
    municipios.municipio,
    provincias.provincia,
    comunidades.comunidad,
    portabilidades.CP_TITULAR,
    portabilidades.NUM_SOLICITUD,
    operadores_telefonia.NOMBRE

FROM portabilidades,municipios,provincias,comunidades,operadores_telefonia
where portabilidades.id=1
AND portabilidades.POBLACION_TITULAR=municipios.id
AND provincias.id=portabilidades.PROV_TITULAR
AND portabilidades.REGION_TITULAR=comunidades.id AND operadores_telefonia.ID=portabilidades.DONANTE
         */
        $u=new UtilPDF();

        return $u->selectWhere3("portabilidades,municipios,provincias,comunidades,operadores_telefonia",
            array("portabilidades.nombre_titular,portabilidades.cif_titular,portabilidades.dir_titular,municipios.municipio,provincias.provincia,
            comunidades.comunidad,portabilidades.cp_titular,portabilidades.num_solicitud,operadores_telefonia.nombre"),
            "portabilidades.id=".$_GET['idPortabilidad']." AND portabilidades.poblacion_titular=municipios.id AND provincias.id=portabilidades.prov_titular AND portabilidades.region_titular=comunidades.id 
             AND operadores_telefonia.id=portabilidades.donante");

    }
}