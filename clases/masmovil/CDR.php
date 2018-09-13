<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 06/09/2018
 * Time: 9:33
 */
require_once 'CDRLinea.php';
class CDR
{
    var $lineas;
    var $msidns;

    /**
     * CDR constructor.
     * @param $lineas
     * @param $msidns
     */
    public function __construct()
    {
        $this->lineas = array();

    }

    /**
     * @return array
     */
    public function getLineas()
    {
        return $this->lineas;
    }

    /**
     * @param array $lineas
     */
    public function setLineas($lineas)
    {
        array_push($this->lineas,$lineas);
    }


}