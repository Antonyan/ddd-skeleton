<?php

namespace Infrastructure\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ExceptionSubscriber  implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::EXCEPTION => array(
                array('logKernelException', 0),
                array('kernelException', -128),
            ),
        );
    }

    public function logKernelException(GetResponseForExceptionEvent $event)
    {
        #TODO
    }

    public function kernelException(GetResponseForExceptionEvent $event)
    {
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