<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 10/09/2018
 * Time: 16:07
 */

class Cobros
{
    public static function getModosCobro()
    {
        $util = new util();
        return $util->selectWhere3('modos_cobro', array('ID','NOMBRE'),'');
    }

}