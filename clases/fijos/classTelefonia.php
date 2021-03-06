<?php
/**
 * Created by PhpStorm.
 * User: telefonia
 * Date: 23/07/2018
 * Time: 9:25
 */

require_once('utilTelefonia.php');


class Telefonia
{
    public $util;

    public $SERVER_TELEFONIA='t1.voipreq.com';

    function __construct() {
       $this->util= new Util();
    }


    /**
     *
     * MÉTODOS PARA SUPERUSUARIOS O REVENTAS
     *
     */

    /**
     * Devuelve el saldo actual prepago del revendedor
     * @param $cifSuperUsuario
     */
    public function getSaldo($cifSuperUsuario){

        $cif="'".$cifSuperUsuario."'";

        $saldo="";
        $saldo=$this->util->selectLast('superusuarios', 'saldo','cif_superuser='.$cif);

        if($saldo=="")
            throw new Exception("Error obteniendo saldo");
        else
            return $saldo;

    }


    /**
     * Devuelve el listado de tarifas para redistribuir de este reventa
     * @param $cifSuperUsuario
     */
    public function getTarifasRedistribucion($cifSuperUsuario){

        $campos=array('grupo','descripcion','prefijo','coste');
        $cifSuperUsuario="'".$cifSuperUsuario."'";
        $tarifas=$this->util->selectWhere('tarifasminimassuperusuario_prueba', $campos,'cif_super='.$cifSuperUsuario, $order=null, $group=null);

        return $tarifas;
    }


    /** METODOS PARA GESTIONAR LAS PORTABILIDADES DE LOS REVENDEDORES */


    /**
     * Devuelve todos los numericos portados para este revendedor, en cualquier estado, ya sea lanzado, portado-libre o asignado
     * @param $cifSuperUsuario
     * @return bool|mysqli_result
     * @throws Exception
     */
    public function getNumericosPortas($cifSuperUsuario){

        if($cifSuperUsuario==""){
            throw new Exception('Reventa vacio');
        }

        $campos=array('numero','estado','fechalanzamiento','fechaportabilidad');
        $numeros=$this->util->selectWhere('numericosportas', $campos,'cif_super='.$cifSuperUsuario, $order=null, $group=null);

        return $numeros;

    }

    /**
     * Devuelve los numericos portados de un reventa segun su estado
     * @param $cifSuperUsuario
     * @param $estado
     * @return int|string
     * @throws Exception
     */
    public function getNumericosPortasEstado($cifSuperUsuario,$estado){

        if($cifSuperUsuario==""){
            throw new Exception('Reventa vacio');
        }
        if($estado==""){
            throw new Exception('Estado vacio');
        }

        $campos=array('numero','estado','fechalanzamiento','fechaportabilidad');
        $numeros=$this->util->selectWhere('numericosportas', $campos,'cif_super='.$cifSuperUsuario.' and estado='.$estado, $order=null, $group=null);

        return $numeros;

    }

    /**
     * Inserta un numerico para portar en la tabla
     * @param $cifSuperUser
     * @param $numero
     * @param $estado
     */
    public function addNumericoPorta($cifSuperUser,$numero,$estado="TRAMITE"){

        if($cifSuperUser==""){
            throw new Exception('Cif superuser vacio');
        }
        if($numero==""){
            throw new Exception('Numero vacio');
        }
        if($estado=="" || ( $estado!="TRAMITE" && $estado!="LANZADA" && $estado!="PORTADO-LIBRE" && $estado!="ASIGNADO")){
            throw new Exception('estado vacio o incorrecto solo aceptamos TRAMITE ,LANZADA ,PORTADO-LIBRE O ASIGNADO');
        }


        //Controlamos que no existe ya un numero en la tabla de portas identico a este

        $cif="'".$cifSuperUser."'";
        $num="'".$numero."'";
        $existe=$this->util->selectLast('numericosportas', 'numerico','numero='.$numero);

        if($existe!="")
            throw new Exception('Ya existe dicho numero en la tabla de portas');

        //si todo ok, procedemos a insertar
        $campos=array('cif_super','numero','estado');
        $values = array($cifSuperUser, $numero,$estado);
        //Insertamos
        $result = $this->util->insertInto('numericosportas', $campos, $values);

        return $result;

    }

    /**
     * Establece un numerico de la tabla de portabilidades a uno de los estados "LANZADA","PORTADO-LIBRE","ASIGNADO"
     * @param $idNumero
     * @param $estado
     */
    public function setNumericoPortaEstado($idNumero,$estado){

        if($idNumero==""){
            throw new Exception('numero vacio');
        }
        if($estado=="" || ( $estado!="TRAMITE" && $estado!="LANZADA" && $estado!="PORTADO-LIBRE" && $estado!="ASIGNADO")){
            throw new Exception('estado vacio o incorrecto solo aceptamos TRAMITE ,LANZADA ,PORTADO-LIBRE O ASIGNADO');
        }

        //Controlamos que  existe dicho numerico
        $existeNum="";
        $existeNum=$this->util->selectLast('numericosportas', 'numero','id_numero='.$idNumero);

        if($existeNum=="")
            throw new Exception('No existe dicho numerico para actualizar');


        //si todo ok, procedemos a updatear

        //si el estado es lanzada meto como ahora mismo la fecha de lanzamiento
        if($estado=="LANZADA"){
            date_default_timezone_set('Europe/Madrid');
            $campos=array('estado','fechalanzamiento');
            $values = array($estado,date('Y-m-d H:i:s'));
        }
        //si el estado es portado libre => pongo ahora mismo como la fecha de porta... esto habria que automatizarlo de alguna forma...
        //que el sistema lo ponga a portado cuando llegue la fecha de portabilidad
        else if($estado=="PORTADO-LIBRE")
        {
            date_default_timezone_set('Europe/Madrid');
            $campos=array('estado','fechaportabilidad');
            $values = array($estado,date('Y-m-d H:i:s'));
        }
        else {
            $campos = array('estado');
            $values = array($estado);
        }
        //Update
        $result = $this->util->update('numericosportas',$campos,$values,'id_numero='.$idNumero,true);


        return $result;


    }


    /******paquetes destinos*******/


    /**
     * Devuelve los paquetes de destino con los que cuenta este reventa
     * @param $cifSuperUsuario
     */
    public function getPaquetesDestino($cifSuperUsuario){

        $campos=array('id_paquetedestino,nombrepaquete');
        $cifSuperUsuario="'".$cifSuperUsuario."'";
        $paquetes=$this->util->selectWhere('paquetesdestino', $campos,'cif_super='.$cifSuperUsuario, $order=null, $group=null);

        return $paquetes;
    }

    /**
     * Crea un paquete de destinos para un superusuario
     * @param $cifSuperUser
     * @param $nbrPaquete
     */
    public function addPaqueteDestino($cifSuperUsuario,$nombrePaquete){

        if($cifSuperUsuario==""){
            throw new Exception('Cif superuser vacio');
        }
        if($nombrePaquete==""){
            throw new Exception('Nombre Paquete vacio');
        }

        //Controlamos que no existe un paquete de destinos con dicho nombre para dicho reventa

        $cif="'".$cifSuperUsuario."'";
        $nombrePack="'".$nombrePaquete."'";
        $existePack=$this->util->selectLast('paquetesdestino', 'nombrepaquete','cif_super='.$cif.' and nombrepaquete='.$nombrePack);

        if($existePack!="")
            throw new Exception('Ya existe un paquete de destinos con dicho nombre');

        //si todo ok, procedemos a insertar
        $campos=array('cif_super','nombrepaquete');
        $values = array($cifSuperUsuario, $nombrePaquete);
        //Insertamos
        $result = $this->util->insertInto('paquetesdestino', $campos, $values);

        return $result;

    }



    /**
     * Actualiza el nombre de un pack de destinos
     * @param $cifSuperUser
     * @param $nbrPaquete
     */
    public function updatePaqueteDestino($idPaqueteDestino,$nombrePaquete){


        if($idPaqueteDestino==""){
            throw new Exception('ID paquete destino vacio');
        }
        if($nombrePaquete==""){
            throw new Exception('nombre Paquete vacio');
        }

        //Controlamos que  existe un paquete de destinos con ID
        $existePack="";
        $existePack=$this->util->selectLast('paquetesdestino', 'nombrepaquete','id_paquetedestino='.$idPaqueteDestino);

        if($existePack=="")
            throw new Exception('No existe un paquete de destinos con dicho ID:$idPaqueteDestino');


        //si todo ok, procedemos a updatear
        $campos=array('nombrepaquete');
        $values = array($nombrePaquete);

        //Update
        $result = $this->util->update('paquetesdestino',$campos,$values,'id_paquetedestino='.$idPaqueteDestino,true);


        return $result;

    }


    /**
     * Elimina un paquete de destinos de un revendedor con id proporcionado
     * @param $cifSuperUsuario
     * @param $idPaqueteDestino
     * @return int
     * @throws Exception
     */
    public function deletePaqueteDestino($idPaqueteDestino){

        if($idPaqueteDestino==""){
            throw new Exception('ID paquete destino vacio');
        }


        //Controlamos que  existe un paquete de destinos con ID
        $existePack="";
        $existePack=$this->util->selectLast('paquetesdestino', 'nombrepaquete','id_paquetedestino='.$idPaqueteDestino);

        if($existePack=="")
            throw new Exception('No existe un paquete de destinos con dicho ID:$idPaqueteDestino');


        //si todo ok, procedemos a eliminar
        //Delete
        $result = $this->util->delete('paquetesdestino', 'id_paquetedestino', $idPaqueteDestino);

        return $result;

    }


    //añadir destino a un paquete

    /**
     * Añade una tarifa a un paquete de destinos
     * @param $idPaqueteDestino
     * @param $grupo
     * @param $descripcion
     * @param $prefijo
     * @param $coste
     */
    public function addPaqueteDestinoTarifa($idPaqueteDestino,$grupo,$descripcion,$prefijo,$coste)
    {
        if($idPaqueteDestino==""){
            throw new Exception('ID paquete destino vacio');
        }
        if($grupo==""){
            throw new Exception('grupo vacio');
        }
        if($descripcion==""){
            throw new Exception('Descripcion vacio');
        }
        if($prefijo==""){
            throw new Exception('Prefijo vacio');
        }
        if($coste==""){
            throw new Exception('Coste vacio');
        }

        //insertamos
        $campos=array('paquetedestino_id','grupo','descripcion','prefijo','coste');
        $values = array($idPaqueteDestino,$grupo,$descripcion,$prefijo,$coste);
        //Insertamos
        $result = $this->util->insertInto('paquetesdestino_tarifas', $campos, $values);

        return $result;


    }

    //eliminar destino de un paquete

    /**
     * Eliminar un destino por prefijo de un paquete de destinos
     * @param $idPaqueteDestino
     * @param $prefijo
     * @return int|void
     * @throws Exception
     */
    public function deletePaqueteDestinoTarifa($idPaqueteDestino,$prefijo)
    {
        if($idPaqueteDestino==""){
            throw new Exception('ID paquete destino vacio');
        }
        if($prefijo==""){
            throw new Exception('Prefijo vacio');
        }

        //Controlamos que  existe dicho destino en el paquete
        $existeDest="";
        $existeDest=$this->util->selectLast('paquetesdestino_tarifas', 'prefijo','prefijo='.$prefijo);

        if($existeDest=="")
            throw new Exception('No existe dicho destino en el paquete');


        //si todo ok, procedemos a eliminar
        //Delete
        $result = $this->util->delete('paquetesdestino_tarifas', 'prefijo', $prefijo);

        return $result;


    }

    /**
     * Autoriza para la troncal indicada los destinos del paquete seleccionado añadiendo el % de beneficio
     * indicado
     * @param $troncal
     * @param $idPaqueteDestino
     * @param $porcentajeBeneficio
     */
    public function setTarifasTroncalFromPaqueteDestinos($troncal,$idPaqueteDestino,$porcentajeBeneficio=0){

        if($idPaqueteDestino==""){
            throw new Exception('ID paquete destino vacio');
        }
        if($troncal==""){
            throw new Exception('Troncal vacio');
        }

        //1 recorremos las tarifas que existen en dicho paquete destino

        $campos=array('grupo','descripcion','prefijo','coste');
        $tarifasEnPaquete=$this->util->selectWhere('paquetesdestino_tarifas', $campos,'paquetedestino_id='.$idPaqueteDestino, $order=null, $group=null);

        $resulTotal=1;
        //cada destino del paquete será añadido a la tabla que indica que destinos estan autorizados para dicha troncal:
        while ($row = mysqli_fetch_array($tarifasEnPaquete)) {
            $grupo=$row[0];
            $descripcion=$row[1];
            $prefijo=$row[2];
            $coste=$row[3];
            //incremento el porcentaje de coste
            $coste = $coste + ($coste*$porcentajeBeneficio/100);

            //inserto:
            $campos=array('usuario_troncal','grupo','descripcion','prefijo','coste','nbrscript');
            $values = array($troncal,$grupo,$descripcion,$prefijo,$coste,"");
            //Insertamos
            $result = $this->util->insertInto('tarifas_prueba', $campos, $values);

            $resulTotal = $resulTotal*$result;
        }

        return $resulTotal;


    }

    /** metodos para interactuar con las tablas de numericos disponibles */

    /**
     * Añade una provincia para que este disponible para añadirle numericos
     * @param $provincia
     */
    public function addProvincia($provincia){

        if($provincia=="")
            throw new Exception("provincia vacia");

        $campos=array('provincia');
        $values = array($provincia);
        //Insertamos
        $result = $this->util->insertInto('numericosdisponiblesprovincias', $campos, $values);

        return $result;
    }


    /**
     * Añade un numerico como disponible en una provincia
     * @param $idProvincia
     * @param $numero
     */
    public function addNumericoDisponiblePorProvincia($idProvincia,$numero){

        if($idProvincia=="")
            throw new Exception("provincia vacia");
        if($numero=="")
            throw new Exception("numero vacio");

        $campos=array('id_provincia','numero','estado');
        $values = array($idProvincia);
        //Insertamos
        $result = $this->util->insertInto('numericosdisponiblesprovincias', $campos, $values);

        return $result;

    }

    /**
     * Devuelve las provincias en las que tenemos numericos
     */
    public function getProvinciasNumericosDisponibles(){

        $campos=array('id_provincia','provincia');
        $provincias=$this->util->selectWhere('numericosdisponiblesprovincias', $campos,true, $order=null, $group=null);

        return $provincias;

    }


    /**
     * Para un ID de provincia dado, devuelve los numericos disponibles existentes
     * @param $idProvincia
     */
    public function getNumericosDisponiblesFromProvincia($idProvincia){

        if($idProvincia==""){
            throw new Exception('ID provincia vacio');
        }

        $campos=array('numero');
        $numeros=$this->util->selectWhere('numericosdisponibles', $campos,'provincia_id='.$idProvincia.' and estado="LIBRE"', $order=null, $group=null);

        return $numeros;

    }

    /**
     * Establece un numerico de la tabla de numeros al estado $estado = "LIBRE" O "ASIGNADO"
     * @param $estado
     */
    public function setNumericoDisponibleEstado($numero,$estado){


        if($numero==""){
            throw new Exception('numero vacio');
        }
        if($estado=="" || ( $estado!="LIBRE" && $estado!="ASIGNADO")){
            throw new Exception('estado vacio o incorrecto solo aceptamos LIBRE O ASIGNADO');
        }

        //Controlamos que  existe dicho numerico
        $existeNum="";
        $existeNum=$this->util->selectLast('numericosdisponibles', 'numero','numero='.$numero);

        if($existeNum=="")
            throw new Exception('No existe dicho numerico para actualizar');


        //si todo ok, procedemos a updatear
        $campos=array('estado');
        $values = array($estado);

        //Update
        $result = $this->util->update('numericosdisponibles',$campos,$values,'numero='.$numero,true);


        return $result;

    }



    /** grupos de recarga */


    /**
     * @param $cifSuperUsuario cif del revendedor o super usuario
     * @return bool|mysqli_result Devuelve los grupos de recarga creados por dicho usuario
     */
    public function getGruposRecarga($cifSuperUsuario){

        $campos=array('nombregrupo');
        $cifSuperUsuario="'".$cifSuperUsuario."'";
        $gruposderecarga=$this->util->selectWhere('gruposderecarga', $campos,'cif_super='.$cifSuperUsuario, $order=null, $group=null);

        return $gruposderecarga;

    }

    /** Crea un grupo de recarga para un superUsuario o Reventa dados unos parametros,
     * lanza una excepcion si ya existe un grupo de recarga con dicho nombre para el reventa indicado
     * Si no lanza excepciones => todo ok, devuelve 1 si todo ha ido ok y ha insertado
     * @param $cifSuperUsuario Cif del reventa
     * @param $nombreGrupo Nombre del grupo de recarga
     * @param $importeRecarga Importe en minutos o euros dependerá del tipo de facturacion del cliente al que se le aplique
     * @param $acumulable Indica si es acumulable o no, es decir, si cuando llegue el dia 1 se suma el importe o se pone a cero el saldo del cliente y luego se suma
     * @param $colorHexadecimal Color en hexadecimal para poner un fondo de color a cada grupo de recarga
     */
    public function addGrupoRecarga($cifSuperUsuario,$nombreGrupo,$importeRecarga,$acumulable,$colorHexadecimal=""){

        if($cifSuperUsuario==""){
            throw new Exception('Cif superuser vacio');
        }
        if($nombreGrupo==""){
            throw new Exception('Nombre Grupo de recarga vacio');
        }
        if($importeRecarga==""){
            throw new Exception('Importe del grupo de recarga vacio');
        }
        if(!is_numeric($importeRecarga)){
            throw new Exception('El Importe ha de ser un numero');
        }
        if($acumulable!="SI" && $acumulable!="NO"){
            throw new Exception('Se debe especificar acumulable SI/NO');
        }
        if($colorHexadecimal==""){
            //ponemos un color por defecto
            $colorHexadecimal="#ff8000";
        }


        //ANTI SAMUELES
        $cifSuperUsuario = $this->util->cleanstring($cifSuperUsuario);
        $nombreGrupo = $this->util->cleanstring($nombreGrupo);
        $importeRecarga = $this->util->cleanstring($importeRecarga);
        $acumulable = $this->util->cleanstring($acumulable);
        $colorHexadecimal = $this->util->cleanstring($colorHexadecimal);

        //Controlamos que no existe un grupo de recarga con dicho nombre para dicho reventa

        $cif="'".$cifSuperUsuario."'";
        $grupo="'".$nombreGrupo."'";
        $existegrupo=$this->util->selectLast('gruposderecarga', 'nombregrupo','cif_super='.$cif.' and nombregrupo='.$grupo);

        if($existegrupo!="")
            throw new Exception('Ya existe un grupo de recarga con dicho nombre');

        //si todo ok, procedemos a insertar
        $campos=array('cif_super','nombregrupo','importerecarga','acumulable','color');
        $values = array($cifSuperUsuario, $nombreGrupo, $importeRecarga, $acumulable, $colorHexadecimal);
        //Insertamos
        $result = $this->util->insertInto('gruposderecarga', $campos, $values);

        return $result;

    }

    /**FALTA edit, update, delete*/


    /**
     *
     * USUARIOS O CLIENTES
     *
     */

    /**
     * Funcion Añadir un cliente a la plataforma
     * @param $cifSuperUsuario
     * @param $cifCliente
     * @param $nombreCliente
     * @param $direccion
     * @param $email
     * @param int $saldoInicial
     * @param int $umbralalerta
     * @param string $noficado
     * @param $tipofacturacion
     * @param $nombregruporecarga
     */
    public function addCliente($cifSuperUsuario,$cifCliente,$nombreCliente,$direccion,$email,
    $umbralAlerta=5,$noficado='no',$tipoFacturacion,$nombreGrupoRecarga){

        if($cifSuperUsuario==""){
            throw new Exception('Cif superuser vacio');
        }
        if($cifCliente==""){
            throw new Exception('Cif cliente vacio');
        }
        if($nombreCliente==""){
            throw new Exception('Nombre Cliente vacio');
        }
        if($direccion==""){
            throw new Exception('direccion vacio');
        }
        if($email ==""){
            throw new Exception(' email vacio');
        }
        if($tipoFacturacion==""){
            throw new Exception('Tipo facturacion vacio');
        }
        if($nombreGrupoRecarga==""){
            throw new Exception(' Grupo de recarga vacio');
        }

        //supongo que los campos ya vienen clean al venir de otra BD y estar seleccionados desde selects


        //verificamos que no exista ya un cliente con dicho CIF para este reventa:

        $cif="'".$cifCliente."'";

        $existeCliente=$this->util->selectLast('usuarios', 'cif','cif='.$cif);

        if($existeCliente!="")
            throw new Exception('Imposible dar alta, Ya existe un cliente con dicho CIF en la plataforma');
        else {

            //procedemos a insertar

            //el saldo inicial sera igual al importe del grupo de recarga ;)
            $cifSuper="'".$cifSuperUsuario."'";
            $grupo = "'".$nombreGrupoRecarga."'";
            $saldoInicial=$this->util->selectLast('gruposderecarga', 'importerecarga','cif_super='.$cifSuper.' and nombregrupo='.$grupo);

            //preparamos e insertamos
            $campos = array('cif_super', 'usuario', 'password', 'nombre', 'cif', 'direccion', 'email', 'saldo', 'umbralalerta', 'notificado',
                'tipofacturacion', 'grupoderecarga');
            $values = array($cifSuperUsuario, 'RE' . $cifCliente, 'p' . $cifCliente, $nombreCliente, $cifCliente, $direccion, $email, $saldoInicial, $umbralAlerta,
                $noficado, $tipoFacturacion, $nombreGrupoRecarga);

            //Insertamos cliente y automaticamente creamos una centralita virtual para él
            $result = $this->util->insertInto('usuarios', $campos, $values);

            //ahora creamos su centralita virtual

            //si la insercion del usuario ha ido bien..
            if($result) {
                $campos = array('cif_user', 'nombre', 'ip_publica', 'habilitado');
                $values = array($cifCliente, 'VIR' . $cifCliente, '89.140.16.1', 'si');
                //creamos la centralita virtual
                $result = $this->util->insertInto('centralitas', $campos, $values);
            }
            else{
                throw new Exception('Error insertando el usuario');
            }

            return $result;
        }

    }

    /**FALTA edit, update, delete*/


    /**
     *
     * LINEAS DE TELEFONO
     *
     */

    /**
     * Añade una linea a un usuario en su centralita virtual, crea el registro en la tabla troncales y numericos
     * @param $cifUsuario
     * @param $usuarioTroncal si no se proporciona se autogenerará, la idea es que se proporcione y sea= ONT
     * @param $passwordTroncal si no se proporciona se autogenerará, la idea es que sea igual a ONT**
     * @param $numero numero de la linea
     */
    public function addLinea($cifUsuario,$usuarioTroncal="",$passwordTroncal="",$numero){

        /*OJOOOOO FALTA EL WEBSERVICE DE ASTERISK!!!!!!*/

        if($cifUsuario==""){
            throw new Exception('Cif usuario vacio');
        }
        if($numero==""){
            throw new Exception('Numero vacio');
        }

        //si no se proporciona se genera
        if($usuarioTroncal==""){
            $usuarioTroncal=$this->util->generateRandomString();
        }
        if($passwordTroncal==""){
            $passwordTroncal="PA".$this->util->generateRandomString(18);
        }

        echo "here";

        if($numero==""){
                throw new Exception("Error numerico vacio");
        }

        echo "por alli";

        //resto de campos para la tabla:
        $cif="'".$cifUsuario."'";
        $idCentralita=$this->util->selectLast('centralitas', 'id_centralita','cif_user='.$cif);
        $codecs="g729,gsm,alaw,ulaw";
        $dialplan="dlpn_".$cifUsuario;
        $servidor_destino=$this->SERVER_TELEFONIA;
        $protocolo="SIP";
        $habilitado='si';
        $operadorsalida='7238#';
        $estado='UP';
        $iporigen='0.0.0.0';
        $fechaactualizacion='0000-00-00 00:00:00';
        $numerollamadas=0;
        $umbralcambiocli=0;

        //preparamos e insertamos
        $campos = array('id_centralita','usuario_troncal','password_troncal','caller_id','codecs','dialplan','servidor_destino',
            'protocolo','habilitado','operadorsalida','estado','iporigen','fechaactualizacion','numero_llamadas','umbral_cambio_cli');

        $values = array($idCentralita,$usuarioTroncal,$passwordTroncal,$numero,$codecs,$dialplan,$servidor_destino,$protocolo,
            $habilitado,$operadorsalida,$estado,$iporigen,$fechaactualizacion,$numerollamadas,$umbralcambiocli);

        //Insertamos la linea
        $result1 = $this->util->insertInto('troncales', $campos, $values);


        //tambien tenemos que añadir el numero como numero de entrada en la tabla numericos
        $result2 = $this->util->insertInto('numericos', array('numero','descripcion'), array($numero,'numerico'));

        return $result1 * $result2;


    }


    /**
     *
     * TARIFAS
     *
     *
     */




}