<?php 

namespace Simplex;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Framework 
{
	protected $dispatcher;
	protected $matcher;
	protected $resolver;

	/**
	 * @param UrlMatcherInterface $matcher 
	 * @param ControllerResolverInterface $resolver 	 
	 */
	public function __construct(
		EventDispatcher $dispatcher,
		UrlMatcherInterface $matcher, 
		ControllerResolverInterface $resolver
	) {
		$this->dispatcher = $dispatcher;
		$this->matcher = $matcher;
		$this->resolver = $resolver;
	}

	/**
	 * Handle incoming request
	 * @param Request $request 
	 * @param array $requestAttributes Aditional request attributes
	 * @return Response
	 */
	public function handle(Request $request, array $requestAttributes=[])
	{
		if ($requestAttributes) {
			$request->attributes->add($requestAttributes);
		}

		$this->matcher->getContext()->fromRequest($request);

		try {
			$request->attributes->add($this->matcher->match($request->getPathInfo()));
			$controller = $this->resolver->getController($request);
			$arguments = $this->resolver->getArguments($request, $controller);

			$response = call_user_func_array($controller, $arguments);
		} catch (ResourceNotFoundException $e) {
			$reponse = new Response('404 Not Found', 404);
		} catch(\Exception $e) {
			$response = new Response('An error occurred: ' . $e->getMessage(), 500);
		}

		$this->dispatcher->dispatch('response', new ResponseEvent($response, $request));

		return $response;
	}
}