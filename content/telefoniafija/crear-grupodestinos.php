<?php
// todo: -------------------------------------------------------------
// funcion que muestra la ficha para poder agregar clientes al sistema
// estos clientes se asocian al revendedor al que esta asociado el usuario que lo crea
// todo: -------------------------------------------------------------



if (!isset($_SESSION)) {
    @session_start();
}

require_once('../../config/util.php');
require_once('../../clases/telefonia/classTelefonia.php');
$util = new util();
$tel = new Telefonia();

// solo los usuarios de nivel 3 a 0 pueden agregar clientes
check_session(3);

?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo OWNER; ?> <?php echo DEF_CLIENTES; ?> / Altas</title>
    <meta name="description" content=""/>
    <meta name="Author" content="<?php echo AUTOR; ?>" />

    <!-- mobile settings -->
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0"/>

    <!-- WEB FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&amp;subset=latin,latin-ext,cyrillic,cyrillic-ext"
          rel="stylesheet" type="text/css"/>

    <!-- CORE CSS -->
    <link href="../../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <!-- THEME CSS -->
    <link href="../../assets/css/essentials.css" rel="stylesheet" type="text/css"/>
    <link href="../../assets/css/layout.css" rel="stylesheet" type="text/css"/>
    <link href="../../assets/css/color_scheme/green.css" rel="stylesheet" type="text/css" id="color_scheme"/>

    <STYLE>

        UL     { cursor: hand; }
        UL LI                { display: none;font: 18pt;list-style: square; }
        UL.showList LI       { display: block; }
        .defaultStyles UL    { color: orange; }
        UL.defaultStyles LI  { display: none; }

        .div_que_centra{
            width: 650px;
            margin: 0 auto;
            text-align: left;
            border: none;
        }

    </STYLE>


</head>
<!--
    .boxed = boxed version
-->
<body>


<!-- WRAPPER -->
<div id="wrapper">

    <aside id="aside" style="position:fixed;left:0">

        <?php require_once('../../menu-izquierdo.php'); ?>

        <span id="asidebg"><!-- aside fixed background --></span>
    </aside>
    <!-- /ASIDE -->


    <!-- HEADER -->
    <header id="header">

        <?php require_once ('../../menu-superior.php');


        ?>

    </header>
    <!-- /HEADER -->


    <!--
        MIDDLE
    -->
    <section id="middle">


        <!-- page title -->
        <header id="page-header">
            <h1>Usted esta en</h1>
            <ol class="breadcrumb">
                <li><a href="#"><?php echo DEF_ALMACEN; ?></a></li>
                <li class="active">Nuevo Grupo Destinos</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">

            <div class="row">

                <div class="col-md-12">

                    <!-- ------ -->
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>Nuevo Grupo Destinos</strong>
                        </div>

                        <div class="panel-body">

                            <!-- todo: ******************************************************************************* -->
                            <!-- los campos del formulario se pasan por POST a php/guardar-cli.php-->
                            <!-- todo: ******************************************************************************* -->


                            <form action="alta_grupodestinos_confirmacion_lista.php" method="post" name="form_cliente" onSubmit=pasarvector()>
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group">

                                            <div class="col-md-6 col-sm-6">
                                                <label>Nombre Grupo: </label>
                                                <input type="text" name="nombregrupodestino" value=""
                                                       class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                        <input name="arv" type="hidden">

                                        <?php
                                        //$tablaprefijos="tablaprefijos_prueba";

                                        echo "<table width='100%' align='center'>";
                                        echo "<caption> Seleccione los destinos autorizados en este grupo </caption>";
                                        echo "</table><br>";




                                        //conectamos a  MYSQL
                                        $link = new mysqli(DB_TELEFONIA_SERVER, DB_TELEFONIA_USER, DB_TELEFONIA_PASSWORD,DB_TELEFONIA_DATABASENAME);

                                        if (mysqli_connect_errno()) {
                                            printf("Falló la conexión: %s\n", mysqli_connect_error());
                                            exit();
                                        }

                                        // Seleccionamos la Base de datos
                                        mysqli_select_db($link,$nombre_bd);

                                        //opcion 2, la wena, nombre de grupo y en desplegable los prefijos existentes

                                        $tablaprefijos="tarifasminimassuperusuario_prueba";

                                        $cif_superuser=$_SESSION['CIF'];
                                        $sel = "select grupo from $tablaprefijos where cif_super='$cif_superuser' group by grupo order by grupo";


                                        $query= mysqli_query($link,$sel);

                                        //while ($row = mysql_fetch_assoc($query))
                                        while ($row = mysqli_fetch_assoc($query))
                                        {
                                            //$row['grupo']
                                            $grup=$row['grupo'];
                                            $grupsinespacios = ereg_replace( "([     ]+)", "", $grup );
                                            echo "<br>";
                                            echo "<div align='left' class='div_que_centra'>";


                                            echo "<ul  style=\"margin-top: -30px\" class='$grupsinespacios'> <INPUT TYPE=CHECKBOX NAME='$nbrgrupsinespacios' onClick=\"marcar_grupo(this.form,'$grupsinespacios',this.checked) \"/>  $grupsinespacios";

                                            $sel2 = "select grupo,prefijo,descripcion,coste from $tablaprefijos where grupo='$grup' and cif_super='$cif_superuser'";

                                            $querye = mysqli_query($link,$sel2);


                                            echo "<li class='$grupsinespacios' id='vacio'>&nbsp;";
                                            //echo "<li class='$grupsinespacios'>";
                                            echo "<table width='100%' class=\"table table-bordered table-hover\">";
                                            echo "<thead><tr><td>Check</td><td>Grupo</td><td>Prefijo</td><td>Descripcion</td><td>Coste(€/min)</td></thead><tbody>";

                                            while ($rowe = mysqli_fetch_assoc($querye))
                                            {
                                                $prefix=$rowe['prefijo'];

                                                echo "<li class='$grupsinespacios' id='$prefix'><tr><td><INPUT TYPE=CHECKBOX NAME='$grupsinespacios' class='$grupsinespacios' onClick=\"li_borrar_prefijo_vector('$prefix',this.checked)\" ></td><td>".$grupsinespacios."</td><td>".$rowe['prefijo']."</td><td>".$rowe['descripcion']."</td><td>".$rowe['coste']."</td></tr>";

                                            }


                                            echo "</tbody>";
                                            echo "</table>";
                                            echo "</ul>";
                                            echo "</div>";


                                        }


                                        mysqli_close($db);



                                        ?>
                                        <br>&nbsp;<br>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit"
                                                    class="btn btn-3d btn-teal btn-xlg btn-block margin-top-30">
                                                CREAR NUEVO GRUPO DE DESTINOS
                                            </button>
                                        </div>
                                    </div>


                                </fieldset>



                            </form>

                        </div>

                    </div>
                    <!-- /----- -->

                </div>



            </div>



        </div>
    </section>
    <!-- /MIDDLE -->

</div>

<!-- JAVASCRIPT FILES -->
<script type="text/javascript">var plugin_path = '../../assets/plugins/';</script>
<script type="text/javascript" src="../../assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="../../assets/js/app.js"></script>

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.js"></script>

<script>
    var prefijos_aceptados = new Array();

    function validar_texto(e){
        tecla = (document.all) ? e.keyCode : e.which;

        //Tecla de retroceso para borrar, siempre la permite
        if (tecla==8){
            return true;
        }

        // Patron de entrada, en este caso solo acepta numeros
        //patron =/[0-9]/;
        patron = /^([0-9])*[.]?[0-9]*$/;

        tecla_final = String.fromCharCode(tecla);

        return patron.test(tecla_final);
    }




    function marcar_grupo(formulario,nombregrupo,status)
    {
        //alert(formulario);
        //alert(nombregrupo);

        var n;
        n=formulario.elements.length;



        for (i=0;i<n;i++)
        {
            if (formulario.elements[i].className.indexOf(nombregrupo) !=-1)
            {
                formulario.elements[i].checked = status;
            }
        }

        //document.getElementById(superul).className = 'defaultStyles';

        var clasedesplegada='showList';
        var claseplegada= 'defaultStyles';

        //Si estoy poniendo a false => se va a cerrar el chequeo => busco el desplegado y lo pliego
        if(status==false)
        {
            var allHTMLTags = new Array();
            //alert("voy a plegar y borrar");

            //Create Array of All HTML Tags
            var allHTMLTags=document.getElementsByTagName("ul");

            //Loop through all tags using a for loop
            for (i=0; i<allHTMLTags.length; i++) {

                //alert(allHTMLTags[i].className);
                //Get all tags with the specified class name.
                //alert(allHTMLTags[i].className);
                if (allHTMLTags[i].className==nombregrupo || allHTMLTags[i].className==clasedesplegada ) {

                    //Place any code you want to apply to all
                    //pages with the class specified.
                    //In this example is to “display:none;” them
                    //Making them all dissapear on the page.
                    //alert("encontrada clase a plegar");

                    //allHTMLTags[i].className = 'defaultStyles';
                    //allHTMLTags[i].style.display ="none";
                    var liNodes = allHTMLTags[i].getElementsByTagName("li");

                    for( var j = 0; j < liNodes.length; j++ )
                    {

                        if (liNodes[j].className.indexOf(nombregrupo) !=-1)
                        {
                            //alert("nodo a plegar: " + j);
                            liNodes[j].style.display ="none";

                            if(liNodes[j].id!='vacio')
                            {

                                //eliminamos dicho elemento en el vector de prefijos
                                //alert("prefijo a borrar" + liNodes[j].id);

                                //recorro el vector y borro
                                for (z=0;z< prefijos_aceptados.length ; z++)
                                {
                                    if(prefijos_aceptados[z]==liNodes[j].id)
                                    {
                                        //alert("borro el prefijo: "+liNodes[j].id +" del vector");
                                        prefijos_aceptados.splice(z,1);
                                    }

                                }

                                //muestro el vector
                                //alert("asi queda ahora el contenido del vector");
                                //for (z=0;z< prefijos_aceptados.length ; z++)
                                //alert("z=" + z + "->> " + prefijos_aceptados[z]);
                            }

                        }
                    }
                }
            }
        }

        //Si estoy poniendo a true => se va a abrir el chequeo => busco el plegado y lo despliego
        if(status==true)
        {
            var allHTMLTags = new Array();
            //alert("busco plegados");

            //Create Array of All HTML Tags
            var allHTMLTags=document.getElementsByTagName("ul");

            //Loop through all tags using a for loop
            for (i=0; i<allHTMLTags.length; i++) {

                //alert(allHTMLTags[i].className);
                //Get all tags with the specified class name.




                if (allHTMLTags[i].className==nombregrupo) {

                    //Place any code you want to apply to all
                    //pages with the class specified.
                    //In this example is to “display:none;” them
                    //Making them all dissapear on the page.
                    //alert("existe uno con la clase plegada");

                    //allHTMLTags[i].className = 'defaultStyles';
                    //allHTMLTags[i].style.display ="none";
                    var liNodes = allHTMLTags[i].getElementsByTagName("li");

                    for( var j = 0; j < liNodes.length; j++ )
                    {

                        if (liNodes[j].className.indexOf(nombregrupo) !=-1)
                        {
                            //alert("subnodo a plegado a desplegar");
                            liNodes[j].style.display ="block";
                            //alert("prefijo" + liNodes[j].id);
                            //añado ese prefijo a la lista de aceptados
                            //uso el id del elemento LI como valor de prefijo
                            if(liNodes[j].id!='vacio')
                            {
                                prefijos_aceptados.push(liNodes[j].id);
                                //muestro el vector
                                //alert("contenido vector");
                                //for (z=0;z< prefijos_aceptados.length ; z++)
                                //alert("z=" + z + "->> " + prefijos_aceptados[z]);
                            }

                        }

                    }
                }
            }
        }




    }


    function li_borrar_prefijo_vector(valor,status)
    {
        //si despico => elimino del vector de prefijos
        if(status==false)
        {
            for (z=0;z< prefijos_aceptados.length ; z++)
            {
                if(prefijos_aceptados[z]==valor)
                {
                    //alert("borro el prefijo: "+valor +" del vector");
                    prefijos_aceptados.splice(z,1);
                }

            }

            //muestro el vector
            //alert("asi queda ahora el contenido del vector");
            //for (z=0;z< prefijos_aceptados.length ; z++)
            //alert("z=" + z + "->> " + prefijos_aceptados[z]);

        }
        else //añado al vector de prefijos => nunca voy a chequear esto estando el prefijo ya en el vector... CORRECTO
        {
            prefijos_aceptados.push(valor);
            //muestro el vector
            //alert("contenido vector");
            //for (z=0;z< prefijos_aceptados.length ; z++)
            //alert("z=" + z + "->> " + prefijos_aceptados[z]);

        }


    }

    function pasarvector()
    {
        var arve = prefijos_aceptados.toString();
        // This line converts js array to String
        document.form_cliente.arv.value=arve;
        // This sets the string to the hidden form field.
    }




</script>




</body>
</html>