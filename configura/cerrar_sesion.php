<?php
    session_start();
    session_unset();
    session_destroy();
    session_commit();
    header("Location:index.php");
    die();
?>