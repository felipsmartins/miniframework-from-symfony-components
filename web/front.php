<?php error_reporting(E_ALL);

$loader = require __DIR__ . '/../autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;

function render_template(Request $request)
{	
	extract($request->attributes->all(), EXTR_SKIP);
	ob_start();
	require sprintf('%s/%s.php', $pagesdir, $_route);

	return new Response(ob_get_clean());
}

$pagesdir =  __DIR__ . '/../src/pages';
$request  = Request::createFromGlobals();
$routes  = include __DIR__ . '/../src/app.php';
$context = new RequestContext();
$matcher  = new UrlMatcher($routes, $context);
$resolver = new HttpKernel\Controller\ControllerResolver();	
	
# Listeners registry
$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new Simplex\ContentLengthListener());
$dispatcher->addSubscriber(new Simplex\GoogleListener());

$framework = new Simplex\Framework($dispatcher, $matcher, $resolver); 
$response = $framework->handle($request, ['pagesdir' => $pagesdir]);

$response->send();