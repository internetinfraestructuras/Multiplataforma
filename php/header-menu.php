<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 13:56
 */
?>

<!-- Mobile Button -->
<button id="mobileMenuBtn"></button>

<!-- Logo -->
<span class="logo pull-left">
					<img src="img/logo.png" alt="admin panel" height="35"/>
				</span>

<form method="get" action="page-search.html" class="search pull-left hidden-xs">
    <input type="text" class="form-control" name="k" placeholder="Encontrar algo..."/>
</form>

<nav>

    <!-- OPTIONS LIST -->
    <ul class="nav pull-right">

        <!-- USER OPTIONS -->
        <li class="dropdown pull-left">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
               data-close-others="true">
                <img class="user-avatar" alt="" src="assets/images/noavatar.jpg" height="34"/>
                <span class="user-name">
									<span class="hidden-xs">
										<?php echo $_SESSION['NOM_USER'];?> <i class="fa fa-angle-down"></i>
									</span>
								</span>
            </a>
            <ul class="dropdown-menu hold-on-click">
                <li><!-- my calendar -->
                    <a href="calendar.html"><i class="fa fa-calendar"></i> Calendário</a>
                </li>
                <li><!-- my inbox -->
                    <a href="#"><i class="fa fa-envelope"></i> Bandeja de entrada
                        <span class="pull-right label label-default">0</span>
                    </a>
                </li>

                <li class="divider"></li>

                <li><!-- lockscreen -->
                    <a href="page-lock.html"><i class="fa fa-lock"></i> Bloquear pantalla</a>
                </li>
                <li><!-- logout -->
                    <a href="logout.php"><i class="fa fa-power-off"></i> Cerrar Sesion</a>
                </li>
            </ul>
        </li>
        <!-- /USER OPTIONS -->

    </ul>
    <!-- /OPTIONS LIST -->

</nav>
