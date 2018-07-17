<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 13/07/2018
 * Time: 11:24
 */

class IptvAPI
{
    var  $urlAPI="http://127.0.0.1/base2017/public/api";

    /**
     * IptvAPI constructor.
     * @param string $urlAPI
     */
    public function __construct()
    {

    }


    public function obtenerIPTVS()
    {
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$this->urlAPI."/iptv");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $contentType="Content-Type:application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($contentType));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $respuesta=curl_exec ($ch);

        return json_decode($respuesta);
    }

    public function obtenerIPTVSReseller($idReseller)
    {
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$this->urlAPI."/usuario/$idReseller/iptvstock");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $contentType="Content-Type:application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($contentType));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $respuesta=curl_exec ($ch);
        return json_decode($respuesta);
    }

}