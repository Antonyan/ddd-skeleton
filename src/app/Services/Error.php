<?php

namespace App\Services;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ErrorController
{
    public function __construct()
    {
        die;
    }

    public function handleHttpException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof HttpExceptionInterface) {
            $event->setResponse(
                new JsonResponse(
                    ['message' => $exception->getMessage()],
                    $exception->getStatusCode(),
                    $exception->getHeaders()
                )
            );
        }
    }

    public function handleAction(FlattenException $exception)
    {die;
        var_dump($exception);die;
        if ($event->getResponse()) {
            return;
        }
        $exception = $event->getException();

        if ($exception instanceof ResourceNotFoundException) {
            $msg = 'Something went wrong! ('.$event->getException()->getMessage().')';
            $event->setResponse(new Response($msg, Response::HTTP_NOT_FOUND));
            return;
        }

        if ($exception instanceof HttpExceptionInterface) {
            $event->setResponse(new Response($exception->getMessage(), $exception->getStatusCode(), $exception->getHeaders()));
            return;
        }

        $event->setResponse(new Response($exception->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR));
    }
}