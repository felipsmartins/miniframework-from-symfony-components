<?php 

namespace Calendar\Controller;

use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\HttpKernel\Exception\FlattenException;
use Symfony\Component\Debug\Exception\FlattenException;

class ErrorController
{
    # doc incorreta, abrir issue: public function exceptionAction(FlattenException $exception)
    public function exceptionAction(FlattenException $exception)
    {
        $msg = 'Something went wrong! ('.$exception->getMessage().')';

        return new Response($msg, $exception->getStatusCode());
    }
}