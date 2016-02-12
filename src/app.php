<?php 
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$routes = new RouteCollection();
$routes->add('hello', new Route('/hello/{name}', [
	'name'        => 'World', 
	'_controller' => 'render_template',
]));
$routes->add('bye', new Route('/bye'));
$routes->add('leap_year', new Route('/is_leap_year/{year}', array(
    'year' => null,
    '_controller' => 'Calendar\\Controller\\LeapYearController::index',
)));
return $routes;