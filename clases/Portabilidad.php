<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 24/08/2018
 * Time: 9:36
 */
error_reporting('0');
ini_set('display_errors', 0);

class Portabilidad
{

    public static function getDatosPortabilidadPDF($idPortabilidad, $tipo)
    {

        $u = new UtilPDF();

        return $u->selectJoin("portabilidades",
            array('FECHA_SOLICITUD', 'NOMBRE_TITULAR', 'CIF_TITULAR', 'CP_TITULAR', 'DIR_TITULAR', 'NUMERO_PORTAR', 'FIRMA',
                'ICC', 'MOVIL_PORTAR', 'MODALIDAD_ORIGEN', 'municipios.municipio', 'provincias.provincia', 'comunidades.comunidad',
                'clientes.NOMBRE', 'clientes.APELLIDOS', 'clientes.DNI', 'clientes.DIRECCION', 'clientes.IBAN', 'clientes.SWIFT',
                'clientes.FIJO', 'clientes.MOVIL', 'clientes.EMAIL', 'clientes.BANCO', 'clientes.FECHA_NACIMIENTO',
                'operadores_telefonia.NOMBRE', 'clientes_tipos.NOMBRE', 'tipos_documentos.NOMBRE', 'porta_tipos_acceso_donante.NOMBRE',
                'servicios.NOMBRE','DC'),
            " left JOIN municipios ON portabilidades.poblacion_titular=municipios.id   
                   left JOIN provincias ON provincias.id=portabilidades.prov_titular 
                   left JOIN comunidades ON portabilidades.region_titular=comunidades.id   
                   left JOIN clientes ON clientes.ID=portabilidades.ID_CLIENTE 
                   left JOIN tipos_documentos ON tipos_documentos.ID=portabilidades.ID_CLIENTE  
                   left JOIN porta_tipos_acceso_donante ON porta_tipos_acceso_donante.ID=portabilidades.TIPO_ACCESO   
                   left JOIN servicios ON servicios.ID=portabilidades.TARIFA 
                   left JOIN clientes_tipos ON clientes_tipos.ID=portabilidades.TIPO_TITULAR 
                   left JOIN clientes_documentos_tipos ON clientes_documentos_tipos.ID=portabilidades.TIPO_DOC 
                   left JOIN operadores_telefonia ON operadores_telefonia.id=portabilidades.donante ", null,
            " portabilidades.id=" . $idPortabilidad);

    }
}
?>