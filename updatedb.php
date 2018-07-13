<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 08/03/2018
 * Time: 9:49
 */

include_once("config/util.php");

$util= new util();


//crear tablas control id en olt
//
//$q="CREATE TABLE control_id_ont (
//  id int(11) NOT NULL,
//  olt int(5) NOT NULL,
//  c int(3) NOT NULL,
//  t int(3) NOT NULL,
//  p int(3) NOT NULL,
//  id_ont int(4) NOT NULL
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;";
//echo $util->consulta();
//
//
//$q="ALTER TABLE control_id_ont
//  ADD PRIMARY KEY (id);";
//echo $util->consulta();
//
//
//$q="ALTER TABLE control_id_ont
//  MODIFY id int(11) NOT NULL AUTO_INCREMENT;COMMIT;";
//echo $util->consulta();
//
//$q="ALTER TABLE control_id_ont CHANGE id_ont id_datos INT(4) NOT NULL;";
//echo $util->consulta();
//
//$q="ALTER TABLE control_id_ont ADD id_voz INT(4) NOT NULL AFTER id_datos, ADD id_tv INT(4) NOT NULL AFTER id_voz;";
//echo $util->consulta();
//
//$q="ALTER TABLE control_id_ont ADD ont_id INT(4) NOT NULL AFTER p;";
//echo $util->consulta();

//$q="ALTER TABLE clientes DROP INDEX dni;";
//echo $util->consulta($q);
//
//$q="ALTER TABLE clientes DROP INDEX email;";
//echo $util->consulta($q);
//
//$q="ALTER TABLE clientes ADD UNIQUE( dni, email, user_create);";
//echo $util->consulta($q);

//for ($n=0;$n<16;$n++) {
//    $q = "INSERT INTO control_id_ont (id, olt, c, t, p, ont_id, id_datos, id_voz, id_tv) VALUES (NULL, '1', '0', '0', ".$n.", '10', '10', '10', '10');";
//    echo $util->consulta($q);
//}
//
//
//for ($n=0;$n<16;$n++) {
//    $q = "INSERT INTO control_id_ont (id, olt, c, t, p, ont_id, id_datos, id_voz, id_tv) VALUES (NULL, '1', '0', '1', ".$n.", '10', '10', '10', '10');";
//    echo $util->consulta($q);
//}
//
//
//for ($n=0;$n<16;$n++) {
//    $q = "INSERT INTO control_id_ont (id, olt, c, t, p, ont_id, id_datos, id_voz, id_tv) VALUES (NULL, '1', '1', '0', ".$n.", '10', '10', '10', '10');";
//    echo $util->consulta($q);
//}
//
//
//for ($n=0;$n<16;$n++) {
//    $q = "INSERT INTO control_id_ont (id, olt, c, t, p, ont_id, id_datos, id_voz, id_tv) VALUES (NULL, '1', '1', '1', ".$n.", '10', '10', '10', '10');";
//    echo $util->consulta($q);
//}
//
//
//
//
//
//for ($n=0;$n<16;$n++) {
//    $q = "INSERT INTO control_id_ont (id, olt, c, t, p, ont_id, id_datos, id_voz, id_tv) VALUES (NULL, '2', '0', '0', ".$n.", '10', '10', '10', '10');";
//    echo $util->consulta($q);
//}
//
//
//for ($n=0;$n<16;$n++) {
//    $q = "INSERT INTO control_id_ont (id, olt, c, t, p, ont_id, id_datos, id_voz, id_tv) VALUES (NULL, '2', '0', '1', ".$n.", '10', '10', '10', '10');";
//    echo $util->consulta($q);
//}
//
//
//for ($n=0;$n<16;$n++) {
//    $q = "INSERT INTO control_id_ont (id, olt, c, t, p, ont_id, id_datos, id_voz, id_tv) VALUES (NULL, '2', '1', '0', ".$n.", '10', '10', '10', '10');";
//    echo $util->consulta($q);
//}
//
//
//for ($n=0;$n<16;$n++) {
//    $q = "INSERT INTO control_id_ont (id, olt, c, t, p, ont_id, id_datos, id_voz, id_tv) VALUES (NULL, '2', '1', '1', ".$n.", '10', '10', '10', '10');";
//    echo $util->consulta($q);
//}

//$q = 'ALTER TABLE `perfil_internet` ADD `bytes_up` VARCHAR(6) NOT NULL AFTER `id_olt`, ADD `bytes_dw` VARCHAR(6) NOT NULL AFTER `bytes_up`;';
//$util->consulta($q);

//$util->consulta("ALTER TABLE `usuarios` CHANGE `email` `email` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NULL;");
//$util->consulta("A"ALTER TABLE `usuarios` CHANGE `email` `email` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NULL;");

//echo $util->consulta("ALTER TABLE `aprovisionados` ADD `num_pon` VARCHAR(20) NOT NULL AFTER `cabecera`;");
//echo $util->consulta("CREATE TABLE `srvprofilesname` (`id` int(11) NOT NULL,`profilename` varchar(10) COLLATE utf8_bin NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
//echo $util->consulta("INSERT INTO `srvprofilesname` (`id`, `profilename`) VALUES(1, 'HG8546M'),(2, 'HG8545M'),(3, 'IPTVM11'), (4, 'IPTVM12');");
//echo $util->consulta("ALTER TABLE `srvprofilesname` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5; COMMIT;");
//echo $util->consulta("update `perfil_internet` set `bytes_up` = `perfil_olt` * 1024");
//echo $util->consulta("update `perfil_internet` set `bytes_dw` = `perfil_olt` * 1024");
//echo $util->consulta("update `perfil_internet` set `bytes_up` = 1024000 where `perfil_olt` = 1000 ");
//echo $util->consulta("update `perfil_internet` set `bytes_dw` = 1024000 where `perfil_olt` = 1000 ");
//
//echo $util->consulta("CREATE TABLE `lineprofiles` (
//                          `id` int(11) NOT NULL,
//                          `nombre_perfil` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
//                          `perfil_olt` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
//                          `nivel_usuario` int(1) NOT NULL,
//                          `id_olt` int(3) NOT NULL,
//                          `bytes_up` varchar(6) COLLATE utf8_spanish2_ci NOT NULL,
//                          `bytes_dw` varchar(6) COLLATE utf8_spanish2_ci NOT NULL
//                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;");
//
//echo $util->consulta("INSERT INTO `lineprofiles` (`id`, `nombre_perfil`, `perfil_olt`, `nivel_usuario`, `id_olt`, `bytes_up`, `bytes_dw`) VALUES
//                        (1, '3 Mb', '3', 0, 4, '3072', '3072'),
//                        (2, '5 Mb', '5', 0, 4, '5120', '5120'),
//                        (3, '10 Mb', '10', 0, 4, '10240', '10240'),
//                        (4, '20 Mb', '20', 0, 4, '20480', '20480'),
//                        (5, '30 Mb', '30', 0, 4, '30720', '30720'),
//                        (6, '40 Mb', '40', 0, 4, '40960', '40960'),
//                        (7, '50 Mb', '50', 0, 4, '51200', '51200'),
//                        (8, '60 Mb', '60', 0, 4, '61440', '61440'),
//                        (9, '70 Mb', '70', 0, 4, '71680', '71680'),
//                        (10, '80 Mb', '80', 0, 4, '81920', '81920'),
//                        (11, '90 Mb', '90', 0, 4, '92160', '92160'),
//                        (12, '100 Mb', '100', 0, 4, '102400', '102400'),
//                        (13, '150 Mb', '150', 0, 4, '153600', '153600'),
//                        (14, '300 Mb', '300', 0, 4, '307200', '307200'),
//                        (15, '400 Mb', '400', 0, 4, '409600', '409600'),
//                        (16, '500 Mb', '500', 0, 4, '512000', '512000'),
//                        (17, '600 Mb', '600', 0, 4, '614400', '614400'),
//                        (18, '700 Mb', '700', 0, 4, '716800', '716800'),
//                        (19, '800 Mb', '800', 0, 4, '819200', '819200'),
//                        (20, '900 Mb', '900', 0, 4, '921600', '921600'),
//                        (21, '1000 Mb', '1000', 0, 4, '102400', '102400');");
//
//echo $util->consulta("ALTER TABLE `lineprofiles` ADD PRIMARY KEY (`id`);");
//echo $util->consulta("ALTER TABLE `lineprofiles` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40; COMMIT;");
//echo $util->consulta("ALTER TABLE `control_id_ont` ADD `id_vpn` INT(5) NOT NULL AFTER `fecha_hora`;");

//echo $util->consulta("CREATE TABLE estado_olts ( `id` INT NOT NULL AUTO_INCREMENT , `id_olt` INT(5) NOT NULL , `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `pon` VARCHAR(16) NOT NULL , `c` VARCHAR(2) NOT NULL , `t` VARCHAR(2) NOT NULL , `p` VARCHAR(2) NOT NULL , `control_flag` VARCHAR(10) NOT NULL , `run_state` VARCHAR(15) NOT NULL , `description` VARCHAR(50) NOT NULL , `cpu` VARCHAR(10) NOT NULL , `mem` VARCHAR(10) NOT NULL , `temp` VARCHAR(10) NOT NULL , `alarma` VARCHAR(50) NOT NULL , `distancia` VARCHAR(10) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
//
//echo $util->consulta("ALTER TABLE `clientes` DROP INDEX `dni`;");
echo $util->consulta("drop table estado_olts");
echo $util->consulta("
CREATE TABLE `estado_olts` (
  `id` int(11) NOT NULL,
  `id_olt` int(5) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pon` varchar(16) COLLATE utf8_bin NOT NULL,
  `c` varchar(2) COLLATE utf8_bin NOT NULL,
  `t` varchar(2) COLLATE utf8_bin NOT NULL,
  `p` varchar(2) COLLATE utf8_bin NOT NULL,
  `control_flag` varchar(10) COLLATE utf8_bin NOT NULL,
  `run_state` varchar(10) COLLATE utf8_bin NOT NULL,
  `description` varchar(50) COLLATE utf8_bin NOT NULL,
  `cpu` varchar(10) COLLATE utf8_bin NOT NULL,
  `mem` varchar(10) COLLATE utf8_bin NOT NULL,
  `temp` varchar(6) COLLATE utf8_bin NOT NULL,
  `alarma` varchar(50) COLLATE utf8_bin NOT NULL,
  `distancia` varchar(10) COLLATE utf8_bin NOT NULL,
  `dbm` varchar(6) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
");
echo $util->consulta("ALTER TABLE `estado_olts`
  ADD PRIMARY KEY (`id`);");
echo $util->consulta("ALTER TABLE `estado_olts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;");


echo $util->consulta("");
echo $util->consulta("");
echo $util->consulta("");
echo $util->consulta("");



