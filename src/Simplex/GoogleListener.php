<?php 

namespace Simplex;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GoogleListener implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return ['response' => 'onResponse'];
	}

	public function onResponse(ResponseEvent $event)
	{
		$response = $event->getResponse();
		$isNotHtmlContentType = ($response->headers->has('Content-Type') && 
			false === strpos($response->headers->get('Content-Type'), 'html'));

		if ($response->isRedirection() 
			|| $isNotHtmlContentType 
			|| 'html' !== $event->getRequest()->getRequestFormat()
		) {
			return;
		} 

		$response->setContent($response->getContent() . '<#GA CODE#>');
	}
}