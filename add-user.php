<?php
//**************************************************************************************
// interfaz que permite a los usuarios root y revendedores crear usuarios de distintos niveles
// estos usuarios estaran asociados a un revendedor y podran acceder a la plataforma con
// un usuario y contraseña.
//**************************************************************************************
if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');
$util = new util();
check_session(1);

?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo OWNER; ?> <?php echo DEF_USUARIOS; ?> / Altas</title>
    <meta name="description" content=""/>
    <meta name="Author" content="<?php echo AUTOR; ?>" />

    <!-- mobile settings -->
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0"/>

    <!-- WEB FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&amp;subset=latin,latin-ext,cyrillic,cyrillic-ext"
          rel="stylesheet" type="text/css"/>

    <!-- CORE CSS -->
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <!-- THEME CSS -->
    <link href="assets/css/essentials.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/layout.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/color_scheme/green.css" rel="stylesheet" type="text/css" id="color_scheme"/>

</head>
<!--
    .boxed = boxed version
-->
<body>


<!-- WRAPPER -->
<div id="wrapper">

    <aside id="aside" style="position:fixed;left:0">

        <?php require_once('menu-izquierdo.php'); ?>

        <span id="asidebg"><!-- aside fixed background --></span>
    </aside>
    <!-- /ASIDE -->


    <!-- HEADER -->
    <header id="header">

        <?php
        require_once ('menu-superior.php');
        $campos=array('revendedores.id','empresa','municipios.municipio');
        $result = $util->selectJoin("revendedores", $campos,"JOIN municipios ON municipios.id = revendedores.localidad ","empresa");
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
                <li><a href="#"><?php echo DEF_USUARIOS; ?></a></li>
                <li class="active">Agregar</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">

            <div class="row">

                <div class="col-md-9">

                    <!-- ------ -->
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>ALTAS <?php echo strtoupper(DEF_USUARIOS); ?></strong>
                        </div>

                        <div class="panel-body">

                            <form class="validate" action="php/guardar-user.php" method="post"
                                  enctype="multipart/form-data" id="formulario" >
                                <fieldset>
                                    <!-- required [php action request] -->
                                    <input type="hidden" name="action" value="usuarios"/>
                                    <div class="row">
                                        <div class="form-group">
                                            <?php if($_SESSION['USER_LEVEL']==0) { ?>
                                                <div class="col-md-6 col-sm-6">
                                                <label><?php echo DEF_REVENDEDOR; ?> </label>
                                                    <select id="" name="usuarios[revendedor]"  class="form-control required">
                                                        <option  disabled selected>Seleccionar uno</option>
                                                        <?php
                                                        $c = 0;
                                                        while ($row = mysqli_fetch_array($result)) {
                                                            if ($c == 0) {
                                                                $ultimo = $row;
                                                                $c = 1;
                                                            }
                                                            echo "<option value='". $row['id']."'>" . $row['empresa'] . " / " . $row['municipio'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <?php } else {
                                                            echo '
                                                                <select id="" name="usuarios[revendedor]"  class="form-control hidden">
                                                                <option value="'.$_SESSION['REVENDEDOR'].'" disabled selected></option>
                                                                </select>';
                                                    } ?>

                                            <div class="col-md-3 col-sm-3">
                                                <label>Nivel de acceso *</label>

                                                <select name="usuarios[nivel]" id="niveles"
                                                        class="form-control pointer required">
                                                    <option  disabled selected>Seleccionar uno</option>
                                                    <?php if($_SESSION['USER_LEVEL']==0)
                                                        $util->carga_select('tipos_usuarios', 'nivel', 'nombre', 'nivel');
                                                    else
                                                        $util->carga_select('tipos_usuarios', 'nivel', 'nombre', 'nivel', 'nivel>'.$_SESSION['USER_LEVEL']);
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <label>Dni</label>
                                                <input type="text" name="usuarios[dni]" 
                                                       class="form-control" placeholder="99999999A">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-4 col-sm-4">
                                                <label>Nombre *</label>
                                                <input type="text" name="usuarios[nombre]" 
                                                       class="form-control required">
                                            </div>
                                            <div class="col-md-5 col-sm-5">
                                                <label>Apellidos *</label>
                                                <input type="text" name="usuarios[apellidos]" 
                                                       class="form-control required">
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <label>Teléfono</label>
                                                <input type="tel" name="usuarios[tel1]"  placeholder="600123456"
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-5 col-sm-4">
                                                <label>Email</label>
                                                <input type="email" name="usuarios[email]" 
                                                       class="form-control">
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <label>Usuario *</label>
                                                <input type="text" name="usuarios[usuario]"  
                                                       class="form-control required">
                                            </div>
                                            <div class="col-md-2 col-sm-3">
                                                <label>Clave *</label>
                                                <input type="password" name="usuarios[pass1]" id="pass1"
                                                       class="form-control required">
                                            </div>
                                            <div class="col-md-2 col-sm-3">
                                                <label>Clave *</label>
                                                <input type="password" name="usuarios[pass2]"  id="pass2" class="required form-control">
                                            </div>
                                        </div>
                                    </div>



                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12 col-sm-12">
                                                <label>Notas </label>
                                                <textarea name="usuarios[notas]" rows="5"
                                                          class="form-control "></textarea>
                                            </div>

                                        </div>
                                    </div>




                                </fieldset>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" onclick="checkpass()"
                                                class="btn btn-3d btn-teal btn-xlg btn-block margin-top-30">
                                            VALIDAR Y GUARDAR
                                            <span class="block font-lato">verifique que toda la información es correcta</span>
                                        </button>
                                    </div>
                                </div>

                            </form>

                        </div>

                    </div>
                    <!-- /----- -->

                </div>

                <div class="col-md-3">

                    <div class="panel panel-default">
                        <div class="panel-body">

                            <h4>Niveles de acceso</h4>
                            <?php if($_SESSION['USER_LEVEL']==0) echo"<p><em><b>Super Admin:</b> Usuario super administrador con permisos para realizar todas las operaciones</em></p>";?>
                            <?php if($_SESSION['USER_LEVEL']==0) echo"<p><em><b>Revendedor:</b> Usuario revendedor, puede crear clientes, asignar equipos a clientes, gestionar perfiles de clientes, etc</em></p>";?>
                            <p><em><b>Operador:</b> Usuario con nivel 3 puede asignar perfiles a clientes, equipos, etc</em></p>
                            <p><em><b>Instalador:</b> Usuario de nivel 4, solo puede aprovisionar ont en las olt</em></p>
                            <p><em><b>Cliente:</b> Usuario de nivel 5, solo puede acceder a su ficha de cliente</em></p>
                            <hr/>
                            <p>

                            </p>

                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-body">

                            <a href="javascript:;" onclick=""
                               class="btn btn-info btn-xs">Ayuda</a>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section>
    <!-- /MIDDLE -->

</div>

<!-- JAVASCRIPT FILES -->
<script type="text/javascript">var plugin_path = 'assets/plugins/';</script>
<script type="text/javascript" src="assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="assets/js/app.js"></script>


<script>

    function checkpass(){
        var pass1=$("#pass1").val();
        var pass2=$("#pass2").val();

        if(pass1=='' || pass2==''){
            _toastr("Teclee las dos contraseñas", "top-right","error",false);
            return;
        }

        if(pass1===pass2)
            $("#formulario").submit();
        else
            _toastr("Las contraseñas no coinciden", "top-right","error",false);

    }


</script>



</body>
</html>