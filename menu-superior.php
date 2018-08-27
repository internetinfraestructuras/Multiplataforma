<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 27/02/2018
 * Time: 8:47
 */
?>
<style>
    .spinner div {
        width: 20px;
        height: 20px;
        position: absolute;
        left: -20px;
        top: 55px;
        background-color: #333;
        border-radius: 50%;
        animation: move 4s infinite cubic-bezier(.2,.64,.81,.23);
        display:block;
    }
    .spinner div:nth-child(2) {
        animation-delay: 150ms;
    }
    .spinner div:nth-child(3) {
        animation-delay: 300ms;
    }
    .spinner div:nth-child(4) {
        animation-delay: 450ms;
    }
    @keyframes move {
        0% {left: 0%;}
        75% {left:100%;}
        100% {left:100%;}
    }
</style>
<header id="header" style="position:fixed;width:100%; z-index:4000000">

    <!-- Mobile Button -->
    <button id="mobileMenuBtn"></button>

    <!-- Logo -->
    <span class="logo pull-left">
            <img src="img/logo.png" alt="admin panel" height="35">
        </span>

    <form method="get" action="page-search.html" class="search pull-left hidden-xs">
        <input type="text" class="form-control" name="k" placeholder="Encontrar algo..." />
    </form>

    <nav>

        <!-- OPTIONS LIST -->
        <ul class="nav pull-right">

            <!-- USER OPTIONS -->
            <li class="dropdown pull-left">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                    <img class="user-avatar" alt="" src="assets/images/noavatar.jpg" height="34" />
                    <span class="user-name">
                            <span class="hidden-xs">
                                <?php echo $_SESSION['NOM_USER'];?> <i class="fa fa-angle-down"></i>
                            </span>
                        </span>
                </a>
                <ul class="dropdown-menu hold-on-click">
                    <li><!-- my calendar -->
                        <a href="#"><i class="fa fa-calendar"></i> Calendário</a>
                    </li>
                    <li><!-- my inbox -->
                        <a href="#"><i class="fa fa-envelope"></i> Bandeja de entrada
                            <span class="pull-right label label-default">0</span>
                        </a>
                    </li>
                    <br>
                    <li class="divider"></li>

                    <li><!-- lockscreen -->
                        <!--                            <a href="#"><i class="fa fa-lock"></i> Bloquear pantalla</a>-->
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
    <div class="spinner" id="animacion1">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
</header>
<script
        src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
        crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
        $(".spinner").css('display','none');
    });


</script>