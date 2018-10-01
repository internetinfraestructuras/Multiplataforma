<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 8:07
 */
require_once('config/define.php');
$root = "/";

?>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
      integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

<!--<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>-->

<script src="/assets/sweetalert/sweetalert2.all.min.js"></script>
<script src="/assets/sweetalert/sweetalert2.min.js"></script>

<link rel="stylesheet" href="/assets/sweetalert/sweetalert2.min.css">

<script>
    function alerta(titulo, texto, icono, botonSi, botonNo, funcionAceptar, funcionCancelar) {

        const swalWithBootstrapButtons = swal.mixin({
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: true
        });

        swalWithBootstrapButtons({
            title: titulo,
            html: texto,
            type: icono,
            showCancelButton: true,
            confirmButtonText: botonSi,
            cancelButtonText: botonNo,
            reverseButtons: true,
            width: 500
        }).then((result) => {
            if (result.value) {
                self[funcionAceptar]();
            } else if (result.dismiss === swal.DismissReason.cancel) {
                self[funcionCancelar]();
            }
        })
    }

    function alertaOk(titulo, texto, icono, botonSi, funcion) {

        const swalWithBootstrapButtons = swal.mixin({
            confirmButtonClass: 'btn btn-success',
            buttonsStyling: true
        });

        swalWithBootstrapButtons({
            title: titulo,
            html: texto,
            type: icono,
            confirmButtonText: botonSi,
            reverseButtons: true,
            width: 500
        }).then((result) => {
            if (result.value) {
                self[funcion]();
            }
        })
    }


    function alerta2(titulo, texto, icono, pie) {

        swal({
            type: icono,
            title: titulo,
            html: texto,
            footer: pie
        })
    }

    function confirmacion(texto, tiempo) {
        swal({
            position: 'top-end',
            type: 'success',
            title: texto,
            showConfirmButton: false,
            timer: tiempo
        });
    }

    function error(texto, tiempo) {
        swal({
            position: 'top-end',
            type: 'error',
            title: texto,
            showConfirmButton: false,
            timer: tiempo
        });
    }




    function alertaTiempo(titulo, texto, tiempo) {
        let timerInterval;
        swal({
            title: titulo,
            html: 'Por favor espere... <strong></strong>',
            timer: tiempo,
            onOpen: () => {
                swal.showLoading();
                timerInterval = setInterval(() => {
                    swal.getContent().querySelector('strong')
                        .textContent = swal.getTimerLeft()
                }, 100)
            },
            onClose: () => {
                clearInterval(timerInterval)
            }
        }).then((result) => {
            if (
                // Read more about handling dismissals
                result.dismiss === swal.DismissReason.timer
            ) {
            }
        })
    }


</script>
<nav id="sideNav"><!-- MAIN MENU -->
    <ul class="nav nav-list">
        <li class="active"><!-- dashboard -->
            <a class="dashboard" href="<?php echo $root; ?>index.php">
                <!-- warning - url used by default by ajax (if eneabled) -->
                <center><span style="font-size: 2em; color: #898989;">AT Control</span></center>
            </a>
        </li>
        <li class="active"><!-- dashboard -->
            <center><span style="color: #898989;">Menú de Gestión</span></center>
        </li>

        <li>
            <a href="#">
                <i class="fas fa-angle-down pull-right"></i>
                <i class="main-icon fa fa-users"></i><span><?php echo MNU_ITEM_3; ?> </span>
            </a>
            <ul><!-- submenus -->
                <li><a href="<?php echo $root; ?>add-clie.php">Agregar</a></li>
                <li><a href="<?php echo $root; ?>edit-clie.php">Modificar</a></li>
                <li><a href="<?php echo $root; ?>listado-clientes.php">Listar</a></li>
                <li><a href="<?php echo $root; ?>del-clie.php">Borrar</a></li>
                <!--            <li><a href="<?php echo $root; ?>cons-clie.php">Consultar</a></li>-->
            </ul>
        </li>

        <li>
            <a href="#">
                <i class="fas fa-angle-down pull-right"></i>
                <i class="main-icon fa fa-shopping-cart"></i><span><?php echo MNU_ITEM_8; ?> </span>
            </a>
            <ul><!-- submenus -->
                <li><a href="<?php echo $root; ?>content/almacen/productos.php">Productos</a></li>
                <li><a href="<?php echo $root; ?>content/almacen/proveedores.php">Proveedores</a></li>
                <li><a href="<?php echo $root; ?>content/almacen/tipos.php">Tipos</a></li>
                <li><a href="<?php echo $root; ?>content/almacen/modelos.php">Modelos</a></li>
                <li><a href="<?php echo $root; ?>content/almacen/atributos.php">Atributos</a></li>

                <!--            <li><a href="<?php echo $root; ?>cons-clie.php">Consultar</a></li>-->
            </ul>
        </li>

        <li>
            <a href="#">
                <i class="fas fa-angle-down pull-right"></i>
                <i class="main-icon fas fa-server"></i><span><?php echo MNU_ITEM_10; ?> </span>
            </a>
            <ul><!-- submenus -->
                <li><a href="<?php echo $root; ?>content/servicios/servicios.php">Servicios</a></li>
                <li><a href="<?php echo $root; ?>content/servicios/proveedores.php">Proveedores</a></li>
                <li><a href="<?php echo $root; ?>content/servicios/paquetes.php">Paquetes</a></li>
                <li><a href="<?php echo $root; ?>content/servicios/listados-moviles.php">CDR´s</a></li>


                <!--            <li><a href="<?php echo $root; ?>cons-clie.php">Consultar</a></li>-->
            </ul>
        </li>
        <!-- add pakito-->
        <li>
            <a href="#">
                <i class="fas fa-angle-down pull-right"></i>
                <i class="main-icon fas fa-phone"></i><span>Telefonia Fija</span>
            </a>
            <ul><!-- submenus -->
                <li><a href="<?php echo $root; ?>content/telefoniafija/gruposderecarga.php">Grupos de Recarga</a></li>
                <li><a href="<?php echo $root; ?>content/telefoniafija/paquetesdestino.php">Paquetes destinos</a></li>



                <!--            <li><a href="<?php echo $root; ?>cons-clie.php">Consultar</a></li>-->
            </ul>
        </li>
        <li>
            <a href="#">
                <i class="fas fa-angle-down pull-right"></i>
                <i class="main-icon fas fa-file-contract"></i><span><?php echo MNU_ITEM_9; ?> </span>
            </a>
            <ul><!-- submenus -->
                <li><a href="<?php echo $root; ?>contratar.php">Alta contrato</a></li>
                <li><a href="<?php echo $root; ?>content/ventas/contratos.php">Lista contratos</a></li>
                <li><a href="<?php echo $root; ?>content/ventas/contratos.php">Contratos</a></li>
                <li><a href="<?php echo $root; ?>content/ventas/campanas.php">Promociones</a></li>
                <li><a href="#">Solicitar Portabilidad</a>
                    <ul>
                        <li><a href="<?php echo $root; ?>content/ventas/porta_fijo.php">Fijo</a></li>
                        <li><a href="<?php echo $root; ?>content/ventas/porta_movil.php">Móvil</a></li>
                    </ul>
                </li>
                <li><a href="<?php echo $root; ?>edit-clie.php">Ver Estado Portabilidad</a></li>
                <li><a href="<?php echo $root; ?>del-clie.php">Borrar</a></li>
                <!--            <li><a href="<?php echo $root; ?>cons-clie.php">Consultar</a></li>-->
            </ul>
        </li>

        <li>
            <a href="#">
                <i class="fas fa-angle-down pull-right"></i>
                <i class="main-icon fas fa-people-carry"></i><span><?php echo MNU_ITEM_11; ?> </span>
            </a>
            <ul><!-- submenus -->
                <li><a href="<?php echo $root; ?>content/trabajos/ordenes-hoy.php">Ordenes de hoy</a></li>
                <li><a href="<?php echo $root; ?>content/trabajos/ordenes.php">Ordenes de trabajo</a></li>


            </ul>
        </li>
        <li>
            <a href="#">
                <i class="fas fa-angle-down pull-right"></i>
                <i class="main-icon fas fa-file-invoice"></i><?php echo MNU_ITEM_13; ?> </span>
            </a>
            <ul><!-- submenus -->
                <li><a href="<?php echo $root; ?>content/facturacion/facturas.php">Facturas</a></li>


            </ul>
        </li>
        <li>
            <a href="#">
                <i class="fas fa-angle-down pull-right"></i>
                <i class="main-icon fas fa-folder"></i><span><?php echo MNU_ITEM_12; ?> </span>
            </a>
            <ul><!-- submenus -->
                <li><a href="<?php echo $root; ?>content/configuracion/configuracion-contratos.php">Contratos</a></li>
                <li>
                    <a href="<?php echo $root; ?>content/configuracion/configuracion-portabilidades.php">Portabilidades</a>
                </li>
                <li><a href="<?php echo $root; ?>content/configuracion/configuracion-facturacion.php">Facturación</a>
                </li>


            </ul>
        </li>


        <?php if (intval($_SESSION['USER_LEVEL']) == 0) { ?>

            <li>
                <a href="#">
                    <i class="fas fa-angle-down pull-right"></i>
                    <i class="main-icon fa fa-usd"></i> <span><?php echo MNU_ITEM_2; ?></span>
                </a>
                <ul><!-- submenus -->
                    <li><a href="<?php echo $root; ?>add-reve.php">Agregar</a></li>
                    <li><a href="<?php echo $root; ?>edit-reve.php">Modificar</a></li>
                    <li><a href="<?php echo $root; ?>list-reve.php">Listar</a></li>
                    <!--                <li><a href="<?php echo $root; ?>cons-reve.php">Consultar</a></li>-->
                </ul>
            </li>
        <?php } ?>

        <?php if (intval($_SESSION['USER_LEVEL']) == 0 || intval($_SESSION['USER_LEVEL']) == 1) { ?>
            <li>
                <a href="#">
                    <i class="fas fa-angle-down pull-right"></i>
                    <i class="main-icon fa fa-user"></i> <span><?php echo MNU_ITEM_1; ?></span>
                </a>
                <ul><!-- submenus -->
                    <li><a href="<?php echo $root; ?>add-user.php">Agregar</a></li>
                    <li><a href="<?php echo $root; ?>edit-user.php">Modificar</a></li>
                    <li><a href="<?php echo $root; ?>list-user.php">Listar</a></li>
                    <!--                <li><a href="<?php echo $root; ?>cons-user.php">Consultar</a></li>-->
                    <?php if (intval($_SESSION['USER_LEVEL']) == 0) {
                        echo '<li><a href="<?php echo $root;?>suplantar.php">Suplantar</a></li>';
                    } ?>
                </ul>
            </li>
        <?php } ?>

    </ul>






