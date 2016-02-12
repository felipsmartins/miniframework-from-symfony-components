<?php 

namespace Simplex\Tests;

use Simplex\Framework;
use PHPUnit_Framework_TestCase as FrameworkTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

class FrameworkTest extends FrameworkTestCase
{
	public function testNotFoundHandling()
	{
		$framework = $this->getFrameworkForException(new ResourceNotFoundException);
		$response = $framework->handle(new Request);
		
		$this->assertEquals(404, $response->getStatusCode());

	}

	private function getFrameworkForException($exception)
	{
		$matcher = $this->getMock(UrlMatcherInterface::class);
		$matcher
			->expects($this->once())
			->method('match')
			->will($this->throwException($exception))
		;
		$matcher
			->expects($this->once())
			->method('getContext')
			->will($this->returnValue($this->getMock(RequestContext::class)))
		;
		$resolver = $this->getMock(ControllerResolverInterface::class);

		return new Framework($matcher, $resolver);
	}

	public function testErrorHandling()
	{
		$framework = $this->getFrameworkForException(new \RuntimeException);
		$response = $framework->handle(new Request);

		$this->assertEquals(500, $response->getStatusCode());
	}


	public function testControllerResponse()
	{
		$matcher = $this->getMock(UrlMatcherInterface::class);
		$matcher
			->expects($this->once())
			->method('match')
			->will($this->returnValue([
				'_route' => 'foo',
				'name' => 'Matt',
				'_controller' => function($name) { return new Response('Hello '.$name); }
			]))
		;
		$matcher
			->expects($this->once())
			->method('getContext')
			->will($this->returnValue($this->getMock(RequestContext::class)))
		;
		$resolver = new ControllerResolver();
		$framework = new Framework($matcher, $resolver);
		$response = $framework->handle(new Request);

		$this->assertEquals(200, $response->getStatusCode());
		$this->assertContains('Hello Matt', $response->getContent());
	}
}