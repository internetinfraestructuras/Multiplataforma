<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 31/08/2018
 * Time: 9:29
 */



error_reporting(E_ALL);
ini_set('display_errors', 0);


$link = new mysqli('5.40.80.11', 'root', 'telereq1430*sql','fibra');
if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}
$link->query("set names 'utf8'");


$query = 'SELECT * from revendedores';

if (!($result = $link->query($query)))
    throw new Exception();

$aItems = array();

while ($row = mysqli_fetch_array($result)) {


    $aItem = array("insert into empresas (ID,               NOMBRE,               APELIIDOS,              CIF,            DIRECCION,                  MUNICIPIO,              PROVINCIA,
                                          COMUNIDAD,        CODIGO_POSTAL,        DIRECCION_FISCAL,       TELEFONO,       WEB,                      LOGO,               NOTAS) values (
                                        '".$row['id']."','".$row['nombre']."','".$row['apellidos']."','".$row['dni']."','".$row['direccion']."','".$row['localidad']."','".$row['provincia']."',
                                        '".$row['region']."','".$row['cp']."','".$row['direccion']."','".$row['tel1']."','".$row['web']."','".$row['logo']."','".$row['notas']."')");

    array_push($aItems, $aItem);

}


$query = 'SELECT * from clientes';

if (!($result = $link->query($query)))
    throw new Exception();


$aItems2 = array();

while ($row = mysqli_fetch_array($result)) {

    $res = $link->query( "select revendedor from usuarios where id=".$row['user_create']);
    $r=mysqli_fetch_array($res);

    $aItem = array("insert into clientes (NOMBRE,APELLIDOS,DNI,DOCUMENTO_URL,DOCUMENTO,TIPO_DOCUMENTO,DIRECCION,
                                          LOCALIDAD,PROVINCIA,COMUNIDAD,IBAN,SWIFT,ID_EMPRESA,CP,FIJO,MOVIL,EMAIL,FECHA_ALTA,FECHA_MODIFICA,
                                          NOTAS,ID_CONSENTIMIENTO,ID_TIPO_CLIENTE) values (
                                        '".$row['nombre']."','".$row['apellidos']."','".$row['dni']."','','','1','".$row['direccion']."',
                                        '".$row['localidad']."','".$row['provincia']."','".$row['region']."','','',$r[0],
                                        '".$row['cp']."','".$row['tel1']."','".$row['tel2']."','".$row['email']."','".$row['fecha_alta']."','".$row['fecha_modificacion']."','".$row['notas']."',1,1)");

    array_push($aItems2, $aItem);

}
$link->close();


$link = new mysqli('5.40.80.11', 'root', 'telereq1430*sql','multiplataforma');

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}
$link->query("set names 'utf8'");

foreach ($aItems as $aItem) {
    $link->query($aItem[0]);
    echo $aItem[0];
}
return;

foreach ($aItems2 as $aItem) {
    echo $link->query($aItem[0]);
}

return;

?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo OWNER; ?> Instalaciones</title>
    <meta name="description" content=""/>
    <meta name="Author" content="<?php echo AUTOR; ?>"/>

    <!-- mobile settings -->
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0"/>

    <!-- WEB FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&amp;subset=latin,latin-ext,cyrillic,cyrillic-ext" rel="stylesheet" type="text/css"/>

    <!-- CORE CSS -->
    <link href="../../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <!-- THEME CSS -->
    <link href="../../assets/css/essentials.css" rel="stylesheet" type="text/css"/>
    <link href="../../assets/css/layout.css" rel="stylesheet" type="text/css"/>
    <link href="../../assets/css/color_scheme/green.css" rel="stylesheet" type="text/css" id="color_scheme"/>

    <style>

    </style>
</head>
<!--
    .boxed = boxed version
-->
<body>


<!-- WRAPPER -->
<div id="wrapper">


    <section id="">


        <!-- page title -->
        <header id="page-header">
            <h1>Usted esta en</h1>
            <ol class="breadcrumb">
                <li><a href="#">Herramientas</a></li>
                <li class="active">Migración</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-body" id="listado">
                                <div id="panel-1" class="panel panel-default">
                                    <div class="panel-heading">
                                    <span class="title elipsis h2">
                                    </span>

                                    </div>
                                    <div class="panel-footer">
                                        <div class="row">
                                            <div class="col-xs-12 col-md-3">
                                                <button class="btn btn-info" onclick="migrar_clientes()">Migrar Clientes</button>
                                            </div>
                                            <div class="col-xs-12 col-md-3">
                                                <button class="btn btn-info" onclick="migrar_cabeceras()">Migrar Cabeceras</button>
                                            </div>
                                            <div class="col-xs-12 col-md-3">
                                                <button class="btn btn-info" onclick="migrar_()"></button>
                                            </div>
                                            <div class="col-xs-12 col-md-3">
                                                <button class="btn btn-info" onclick="migrar_clientes()"></button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
            </form>

        </div>
</div>

        <div id="content" class="padding-20">
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-body" id="listado">
                            <div id="panel-1" class="panel panel-default">
                                <div class="panel-heading">
                                    <span class="title  h2">Selecciona uno, algunos o todos los registros
                                    </span>

                                </div>
                                <div class="panel-footer">
                                    <div class="row" id="aqui_los_datos">

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                </form>

            </div>
        </div>

</section>
<!-- /MIDDLE -->

</div>

<!-- JAVASCRIPT FILES-->


<script type="text/javascript">var plugin_path = '../../assets/plugins/';</script>


<script type="text/javascript" src="../../assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="../../assets/js/app.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/jquery.dataTables.css">

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.js"></script>
<script>

    function aprovisionar(boton, id) {

        $(boton).attr("disabled", true);
        boton.textContent = "Espere...";

        $("#ordenar").submit();

    }


</script>


</body>
</html>