<?php

/**
 * Sites bootstrap file
 * @author kaju konwar
 * @version 1.0.0
 */
if (!isset($_SESSION)) session_start();
require_once __DIR__.'/vendor/autoload.php';
// Specify our Twig templates location
$loader = new Twig_Loader_Filesystem(__DIR__.'/views');
$twig = new Twig_Environment($loader);