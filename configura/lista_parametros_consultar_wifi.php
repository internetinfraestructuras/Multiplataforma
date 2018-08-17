<?php
/**
 * Created by PhpStorm']['
 * User: Ruben
 * Date: 19/06/2018
 * Time: 10:38
 */
//InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.AssociatedDevice.3.X_HW_AssociatedDevicedescriptions

$n=0;

/*74 -  0*/$a[$n]=$json[0]['InternetGatewayDevice']['LANDevice'][1]['WLANConfiguration']['Enable']['_value'];$n++;
/*75 -  1*/$a[$n]=$json[0]['InternetGatewayDevice']['LANDevice'][1]['WLANConfiguration']['RegulatoryDomain']['_value'];$n++;
/*76 -  2*/$a[$n]=$json[0]['InternetGatewayDevice']['LANDevice'][1]['WLANConfiguration'][1]['SSID']['_value'];$n++;
/*77 -  3*/$a[$n]=$json[0]['InternetGatewayDevice']['LANDevice'][1]['WLANConfiguration'][1]['PreSharedKey'][1]['PreSharedKey']['_value'];$n++;
/*78 -  4*/$a[$n]=$json[0]['InternetGatewayDevice']['LANDevice'][1]['WLANConfiguration'][1]['PreSharedKey'][1]['KeyPassphrase']['_value'];$n++;
/*79 -  5*/$a[$n]=$json[0]['InternetGatewayDevice']['LANDevice'][1]['WLANConfiguration'][1]['PreSharedKey'][1]['KeyPassphrase']['_value'];$n++;
/*80 -  6*/$a[$n]=$json[0]['InternetGatewayDevice']['X_HW_MainUPnP']['Enable']['_value'];$n++;
/*81 -  7*/$a[$n]=$json[0]['InternetGatewayDevice']['X_HW_SlvUPnP']['Enable']['_value'];$n++;
/*82 -  8*/$a[$n]=$json[0]['InternetGatewayDevice']['LANDevice'][1]['WLANConfiguration'][1]['SSIDAdvertisementEnabled']['_value'];$n++;      // muestra o oculta el ssid
/*83 -  9*/$a[$n]=$json[0]['InternetGatewayDevice']['LANDevice'][1]['WLANConfiguration'][1]['BeaconType']['_value'];$n++;      // tipo seguridad wifi
/*84 - 10*/$a[$n]=$json[0]['InternetGatewayDevice']['LANDevice'][1]['WLANConfiguration'][1]['IEEE11iEncryptionModes']['_value'];$n++;      // encriptacion
/*85 - 11*/$a[$n]=$json[0]['InternetGatewayDevice']['LANDevice'][1]['WLANConfiguration'][1]['WPS']['Enable']['_value'];$n++;      // wps enable/disable
/*86 - 12*/$a[$n]=$json[0]['InternetGatewayDevice']['LANDevice'][1]['LANHostConfigManagement']['IPInterface'][1]['IPInterfaceIPAddress']['_value'];$n++;      // ip gestion router
/*87 - 13*/$a[$n]=$json[0]['InternetGatewayDevice']['LANDevice'][1]['LANHostConfigManagement']['IPInterface'][1]['IPInterfaceSubnetMask']['_value'];$n++;      // mascara
/*88 - 14*/$a[$n]=$json[0]['InternetGatewayDevice']['LANDevice'][1]['LANHostConfigManagement']['MinAddress']['_value'];$n++;      // dhcp start
/*89 - 15*/$a[$n]=$json[0]['InternetGatewayDevice']['LANDevice'][1]['LANHostConfigManagement']['MaxAddress']['_value'];$n++;      // dhcp end

//lan
/*103 - 16*/$a[$n]=$json[0]['InternetGatewayDevice']['LANDevice'][1]['LANHostConfigManagement']['DHCPLeaseTime']['_value'];$n++;      // tiempo de concesion dhcp segundos
/*104 - 17*/$a[$n]=$json[0]['InternetGatewayDevice']['LANDevice'][1]['LANHostConfigManagement']['DNSServers']['_value'];$n++;      // servidores dns separados coma:
/*105 - 18*/$a[$n]=$json[0]['InternetGatewayDevice']['LANDevice'][1]['LANHostConfigManagement']['DHCPServerEnable']['_value'];$n++;      // servidor dhcp
