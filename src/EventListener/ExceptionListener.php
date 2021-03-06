<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $response = new Response();

        $message = sprintf(
            'An error has occured: %s, code: %s',
            $exception->getMessage(),
            $exception->getCode()
        );

        $response->setContent(json_encode([
            'message' => $message
        ]));
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $response->headers->set('Content-Type', 'application/json');

        $event->setResponse($response);
    }
}