<?php 

namespace Simplex;

use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;


class Framework extends HttpKernel\HttpKernel
{
	public function __construct($routes)
    {
    	$requestStack = new RequestStack();
        $context = new Routing\RequestContext();
        $matcher = new Routing\Matcher\UrlMatcher($routes, $context);
        $resolver = new HttpKernel\Controller\ControllerResolver();
       

		# events registry
		$dispatcher = new EventDispatcher();
		$dispatcher->addSubscriber(
			new HttpKernel\EventListener\RouterListener($matcher, $requestStack));
		$dispatcher->addSubscriber(new HttpKernel\EventListener\ExceptionListener(
			'Calendar\\Controller\\ErrorController::exceptionAction'));
		$dispatcher->addSubscriber(new HttpKernel\EventListener\ResponseListener('UTF-8'));
		$dispatcher->addSubscriber(new StringResponseListener());

        parent::__construct($dispatcher, $resolver);
    }
}