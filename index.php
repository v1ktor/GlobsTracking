<?php
require_once 'autoloader.php';

$loader = new \Psr4AutoloaderClass;
$loader->register();
$loader->addNamespace('GlobsTracking', 'GlobsTracking/src');

$front_controller = new \GlobsTracking\Globs\FrontController();
$front_controller->run();