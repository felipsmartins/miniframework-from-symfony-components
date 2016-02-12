<?php 

/** @var Composer\Autoload\ClassLoader */
$loader = require 'vendor/autoload.php';
$loader->addPsr4("", "src/");

return $loader;

