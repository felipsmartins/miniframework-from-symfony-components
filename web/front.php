<?php error_reporting(E_ALL);

$loader = require __DIR__ . '/../autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;

function render_template(Request $request)
{	
	extract($request->attributes->all(), EXTR_SKIP);
	ob_start();
	require sprintf('%s/%s.php', $pagesdir, $_route);

	return new Response(ob_get_clean());
}

$pagesdir =  __DIR__ . '/../src/pages';
$request  = Request::createFromGlobals();
$requestStack = new RequestStack();
$routes  = include __DIR__ . '/../src/app.php';

$context = new Routing\RequestContext();
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);
$resolver = new HttpKernel\Controller\ControllerResolver();

#TOFIX: hmm... Isso deveria está aqui mesmo?	
$request->attributes->add(['pagesdir' => $pagesdir]);

$errorHandler = function (HttpKernel\Exception\FlattenException $exception) {
    $msg = 'Something went wrong! ('.$exception->getMessage().')';

    return new Response($msg, $exception->getStatusCode());
};


# Listeners registry
$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(
	new HttpKernel\EventListener\RouterListener($matcher, $requestStack));
$dispatcher->addSubscriber(new HttpKernel\EventListener\ExceptionListener(
	'Calendar\\Controller\\ErrorController::exceptionAction'));
$dispatcher->addSubscriber(new HttpKernel\EventListener\ResponseListener('UTF-8'));
$dispatcher->addSubscriber(new Simplex\StringResponseListener());

$framework = new Simplex\Framework($dispatcher, $resolver);

$response = $framework->handle($request);
$response->send();
