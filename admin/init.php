<?php

    include 'conect.php';

    // routes

    $tpl = 'includes/templates'; // template directory
    $func = 'includes/functions';
    $css = 'layout/css';
    $js = 'layout/js';
    include $func . "/func.php";
    include $tpl . "/header.inc.php";
    

    if(!isset($noNavbar)) {include $tpl . '/navbar.php';}    