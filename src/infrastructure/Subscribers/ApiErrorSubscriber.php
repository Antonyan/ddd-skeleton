<?php
namespace Infrastructure\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiError implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::EXCEPTION => array(
                array('handleHttpException', 1),
            ),
        );
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
}