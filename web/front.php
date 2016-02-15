<?php 
if ('dev' == getenv('SYMFONY_ENV')) error_reporting(E_ALL);

$loader = require __DIR__ . '/../autoload.php';

use Symfony\Component\HttpFoundation\Request;

$pagesdir =  __DIR__ . '/../src/pages';
$request  = Request::createFromGlobals();

# TOFIX: mover para local apropriado depois	
$request->attributes->add(['pagesdir' => $pagesdir]);

# Dependency injection container
$serviceContainer = include __DIR__ . '/../src/container.php';

$framework = $serviceContainer->get('framework');
$framework->handle($request)->send();
