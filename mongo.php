<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/05/2018
 * Time: 8:32
// */
//phpinfo();
//
$manager = new MongoDB\Driver\Manager("mongodb://5.40.80.171");
var_dump($manager);

return;

// conectar
$m = new MongoClient("mongodb://5.40.80.171" );

// seleccionar una base de datos
$bd = $m->genieacs;

// seleccionar una colección (equivalente a una tabla en una base de datos relacional)
$colección = $bd->devices;
//
//// añadir un registro
//$documento = array( "title" => "Calvin and Hobbes", "author" => "Bill Watterson" );
//$colección->insert($documento);
//
//// añadir un nuevo registro, con un distinto "perfil"
//$documento = array( "title" => "XKCD", "online" => true );
//$colección->insert($documento);

// encontrar todo lo que haya en la colección
$cursor = $colección->find();

// recorrer el resultado
foreach ($cursor as $documento) {
    echo $documento[0] . "\n";
}

?>

<script>


</script>
