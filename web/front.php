<?php 
if ('dev' == getenv('SYMFONY_ENV')) error_reporting(E_ALL);

$loader = require __DIR__ . '/../autoload.php';

use Symfony\Component\HttpFoundation\Request;


$pagesdir =  __DIR__ . '/../src/pages';
$request  = Request::createFromGlobals();
$routes  = include __DIR__ . '/../src/app.php';

#TOFIX: hmm... Isso deveria está aqui mesmo?	
$request->attributes->add(['pagesdir' => $pagesdir]);


$framework = new Simplex\Framework($routes);
$framework->handle($request)->send();
