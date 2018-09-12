<?php
/**
 * Generacion de fichero de adeudos domiciliados
 *
 * Este módulo genera un fichero de adeudos domiciliados según
 * el formato del Cuaderno 19 de la Asociación Española de Banca
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @package    c19.class.php
 * @author     Eduardo Gonzalez <egonzalez@cyberpymes.com>
 * @copyright  2010 CyberPymes
 * @version    1.2.1
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 3
 *
 * @modified   Marzo 2012, by José Fernando Moyano <fernando@zauber.es>
**/

class C19
{

        var $id;
        var $fecha;
        var $total;
        var $numeroAdeudos;
        var $presentador;
        var $idPresentador;
        var $metodoPago;
        var $tipoRemesa; //CORE,COR1 o B2Bç
        var $fechaCobro;
        var $acreedor;
        var $ibanPresentador;
        var $bicAcreedor;
        var $ibanAcreedor;
        var $refPrimerAdeudo;

        var $xml;

    /**
     * C19 constructor.
     */
    public function __construct()
    {
            $this->xml=new DomDocument('1.0', 'UTF-8');

    }



}

?>
