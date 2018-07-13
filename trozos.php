<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 27/02/2018
 * Time: 10:03
 */



                                set_time_limit(1500);
                                $auth_pass='private';
                                $priv_pass='private';
                                ini_set('memory_limit', '256M');
                                $ip = '10.201.112.2'; 		// ip address or hostname
                                $community = 'public';		// community string

                                $oid = SYS_DESCRYPT;		// only numerical oids are supported
                                $snmp = new snmp();
                                $snmp->version = SNMP_VERSION_2;
                                $private='private';
                                echo "<h2>".$oid."</h2>";
                                $devuelto = $snmp->walk($ip, $oid, ['community' => $private]);
                                foreach($devuelto as $dato=>$valor){
                                    if($valor!=="")
                                        echo $dato ."----". $valor . "<br>";
                                }
                                echo "Fin";
                                echo $snmp->set($ip,'.1.3.6.1.4.1.2011.6.128.1.1.2.43.1.17.4194307840.1', 'NUEVO', 'x',['community' => $private]);

                                function hexToStr($hex){
                                    $string='';
                                    for ($i=0; $i < strlen($hex)-1; $i+=2){
                                        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
                                    }
                                    return $string;
                                }
                                function strToHex($string){
                                    $hex='';
                                    for ($i=0; $i < strlen($string); $i++){
                                        $hex .= dechex(ord($string[$i]));
                                    }
                                    return $hex;
                                }


                                ?>