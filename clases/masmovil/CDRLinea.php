<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 06/09/2018
 * Time: 8:34
 */

class CDRLinea
{

    var $tipo;
    var $origen;
    var $destino;
    var $tarifa;
    var $fecha;
    var $hora;
    var $tiempo;
    var $trafico;
    var $velocidad;
    var $detalle;

    /**
     * CDRLinea constructor.
     * @param $tipo
     * @param $origen
     * @param $destino
     * @param $tarifa
     * @param $fecha
     * @param $hora
     * @param $tiempo
     * @param $bajada
     * @param $velocidad
     * @param $detalle
     */
    public function __construct($tipo, $origen, $destino, $tarifa, $fecha, $hora, $tiempo, $trafico, $velocidad, $detalle)
    {
        $this->tipo = $tipo;
        $this->origen = $origen;
        $this->destino = $destino;
        $this->tarifa = $tarifa;
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->tiempo = $tiempo;
        $this->trafico=$trafico;
        $this->velocidad = $velocidad;
        $this->detalle = $detalle;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getOrigen()
    {
        return $this->origen;
    }

    /**
     * @param mixed $origen
     */
    public function setOrigen($origen)
    {
        $this->origen = $origen;
    }

    /**
     * @return mixed
     */
    public function getDestino()
    {
        return $this->destino;
    }

    /**
     * @param mixed $destino
     */
    public function setDestino($destino)
    {
        $this->destino = $destino;
    }

    /**
     * @return mixed
     */
    public function getTarifa()
    {
        return $this->tarifa;
    }

    /**
     * @param mixed $tarifa
     */
    public function setTarifa($tarifa)
    {
        $this->tarifa = $tarifa;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    /**
     * @return mixed
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * @param mixed $hora
     */
    public function setHora($hora)
    {
        $this->hora = $hora;
    }

    /**
     * @return mixed
     */
    public function getTiempo()
    {
        return $this->tiempo;
    }

    /**
     * @param mixed $tiempo
     */
    public function setTiempo($tiempo)
    {
        $this->tiempo = $tiempo;
    }

    /**
     * @return mixed
     */
    public function getTrafico()
    {
        return $this->trafico;
    }

    /**
     * @param mixed $bajada
     */
    public function setTrafico($trafico)
    {
        $this->trafico = $trafico;
    }

    /**
     * @return mixed
     */
    public function getVelocidad()
    {
        return $this->velocidad;
    }

    /**
     * @param mixed $velocidad
     */
    public function setVelocidad($velocidad)
    {
        $this->velocidad = $velocidad;
    }

    /**
     * @return mixed
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * @param mixed $detalle
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;
    }




}