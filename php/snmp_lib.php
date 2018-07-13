<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 20/02/2018
 * Time: 11:25
 */

class snmp_lib {

    private $snmpInstance;
    private $VERSION = SNMP::VERSION_2c;
    private $HOST = '10.201.112.2';
    private $passwordRead = '1ppserrano*';
    private $passwordWrite = '1ppserrano*';
    private $releys = array(1 => '1.3.6.1.4.1.19865.1.2.1.1.0',
        2 => '1.3.6.1.4.1.19865.1.2.1.2.0');
    private $allPorts = array('3' => '1.3.6.1.4.1.19865.1.2.1.33.0',
        '5' => '1.3.6.1.4.1.19865.1.2.2.33.0');

    /**
     * Create instance of SNMP native class, based on actions that we will
     * perform.
     *
     * @param string $action
     */
    public function __construct($action) {
        if (in_array($action, array('read', 'write'))) {
            if (strcmp($action, 'read') === 0) {
                $this->_read();
            } else {
                $this->_write();
            }
        }
    }

    /**
     * Create instance with reading permissions.
     */
    private function _read() {
        $this->snmpInstance = new SNMP($this->VERSION, $this->HOST, $this->passwordRead);
    }

    /**
     * Create instance with writing permissions.
     */
    private function _write() {
        $this->snmpInstance = new SNMP($this->VERSION, $this->HOST, $this->passwordWrite);
    }

    /**
     * Close snmp session.
     *
     * @return boolean
     */
    public function closeSession() {
        return $this->snmpInstance->close();
    }

    /**
     * Set integer 1 as value of defined pin.
     */
    public function activate($relay) {
        $this->snmpInstance->set($this->releys[$relay], 'i', '1');
    }

    /**
     * Set integer 0 as value of defined pin.
     */
    public function deactivate($relay) {
        $this->snmpInstance->set($this->releys[$relay], 'i', '0');
    }

    /**
     * Get pin status of all ports of the module.
     *
     * @return array
     */
    public function getAllPortsStatus() {
        $allPins = array();
        foreach ($this->allPorts as $number => $port) {
            //get active pins as 8-bit integer of defined port
            $getbits = $this->snmpInstance->get($port);
            $bits = str_replace('INTEGER: ', '', $getbits);
            //get pins status
            $pinsStatus = $this->_getActivePins($bits);
            $allPins[$number] = $pinsStatus;
        }

        return $allPins;
    }

    /**
     * Make bitwise operation which will determine,
     * which are active pins.
     *
     * @param int $bits
     * @return array
     */
    private function _getActivePins($bits) {

        $bitMapping = array(
            1 => 1,
            2 => 2,
            3 => 4,
            4 => 8,
            5 => 16,
            6 => 32,
            7 => 64,
            8 => 128
        );

        $pinsStatus = array();

        foreach ($bitMapping as $int => $bit) {
            if (($bits & $bit) == $bit) {
                $pinsStatus[$int] = true;
                continue;
            }
            $pinsStatus[$int] = false;
        }

        return $pinsStatus;
    }

//    funciones propias de Ruben Corrales

    public function alarmas() {
        return $this->snmpInstance->get('display alarm history alarmtype');
    }
    public function sysDesc() {
        return $this->snmpInstance->get(array("sysDescr.0"));
    }
}

?>