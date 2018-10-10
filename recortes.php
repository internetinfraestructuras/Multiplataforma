<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 04/10/2018
 * Time: 13:47
 */

     <div id="panel-2" class="panel panel-default">
                                <div class="panel-heading" >
									<span class="title elipsis">
										<strong>Portabilidades</strong> <!-- panel title -->
									</span>

                                    <!-- tabs nav -->
                                    <ul class="nav nav-tabs pull-right">
                                        <li class="active"><!-- TAB 1 -->
                                            <a href="#porta_curso" data-toggle="tab">En Curso</a>
                                        </li>
                                        <li class=""><!-- TAB 2 -->
                                            <a href="#porta_completas" data-toggle="tab">Completadas</a>
                                        </li>
                                    </ul>
                                    <!-- /tabs nav -->


                                </div>

                                <!-- panel content -->
                                <div class="panel-body" style="height:400px">

                                    <!-- tabs content -->
                                    <div class="tab-content transparent">

                                        <div id="porta_curso" class="tab-pane active" ><!-- TAB 1 CONTENT -->

                                            <div class="table-responsive" style="height:380px; overflow-y: scroll; overflow-x: hidden">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>Estado</th>
                                                        <th>Fecha</th>
                                                        <th>Cliente</th>
                                                        <th>Número</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        $estados = array("SOLICITADA","TRAMITADA","PROCESANDO","RECHAZADA","ACEPTADA","CONTRATADA");
                                                        $estadosOrdenes = array("PENDIENTE","ASIGNADA","EN TRAMITE","CERRADA","INCIDENCIA","CANCELADA");
                                                        $estadosColores = array("label label-primary","label label-info","label label-warning","label label-danger","label label-success","label label-default");
                                                        $estadosColoresOrdenes = array("label label-primary","label label-info","label label-warning","label label-success","label label-danger","label label-default");
                                                        $portas = $util->selectJoin("portabilidades",
                                                        array('FECHA_SOLICITUD', 'NOMBRE_TITULAR',  'NUMERO_PORTAR','clientes.NOMBRE', 'clientes.APELLIDOS','clientes.EMAIL','portabilidades.ESTADO','portabilidades.ID'),
                                                        " left JOIN clientes ON clientes.ID=portabilidades.ID_CLIENTE", 'FECHA_SOLICITUD','ESTADO != 6 AND portabilidades.ID_EMPRESA='.$_SESSION['USER_ID']);

                                                        while ($row = mysqli_fetch_array( $portas)) {
                                                            echo '
                                                                <tr >
                                                                    <td><span class="'.$estadosColores[intval($row[6])-1].'">'.$estados[intval($row[6])-1].'</span></td >
                                                                    <td >'.$util->fecha_eur($row[0]).'</td >
                                                                    <td >'.$row[3].' '.$row[4].'</td >
                                                                    <td >'.$row[2].'</td >
                                                                    <td ><span class="btn btn-default btn-xs btn-block" onclick="ver_mas('.$row[7].');" > Más</span ></td >
                                                                </tr >';
                                                    }
                                                    ?>


                                                    </tbody>
                                                </table>

                                                <a class="size-12" href="#">
                                                    <i class="fa fa-arrow-right text-muted"></i>
                                                    Ver todo
                                                </a>

                                            </div>

                                        </div><!-- /TAB 1 CONTENT -->

                                        <div id="porta_completas" class="tab-pane"><!-- TAB 2 CONTENT -->

                                            <div class="table-responsive" style="height:380px; overflow-y: scroll; overflow-x: hidden">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                    <tr>
                                                        <th>Estado</th>
                                                        <th>Fecha</th>
                                                        <th>Cliente</th>
                                                        <th>Número</th>
                                                        <th></th>
                                                    </tr>
                                                    </tr>
                                                    </thead>
                                                   <tbody>
                                                   <?php

                                                   $portas = $util->selectJoin("portabilidades",
                                                       array('FECHA_SOLICITUD', 'NOMBRE_TITULAR',  'NUMERO_PORTAR','clientes.NOMBRE', 'clientes.APELLIDOS','clientes.EMAIL','portabilidades.ESTADO','portabilidades.ID'),
                                                       " left JOIN clientes ON clientes.ID=portabilidades.ID_CLIENTE", 'FECHA_SOLICITUD','ESTADO = 6 AND portabilidades.ID_EMPRESA='.$_SESSION['USER_ID']);

                                                   while ($row = mysqli_fetch_array( $portas)) {
                                                       echo '
                                                                <tr >
                                                                    <td><span class="'.$estadosColores[intval($row[6])-1].'">'.$estados[intval($row[6])-1].'</span></td >
                                                                    <td >'.$util->fecha_eur($row[0]).'</td >
                                                                    <td >'.$row[3].' '.$row[4].'</td >
                                                                    <td >'.$row[2].'</td >
                                                                    <td ><span class="btn btn-default btn-xs btn-block" onclick="ver_mas('.$row[7].');" > Más</span ></td >
                                                                </tr >';
                                                   }
                                                   ?>
                                                   </tbody>
                                                </table>

                                                <a class="size-12" href="#">
                                                    <i class="fa fa-arrow-right text-muted"></i>
                                                    More Most Visited
                                                </a>

                                            </div>

                                        </div><!-- /TAB 1 CONTENT -->

                                    </div>
                                    <!-- /tabs content -->

                                </div>
                                <!-- /panel content -->

                            </div>