<?php

namespace Infrastructure\Listeners;

use Doctrine\Common\Annotations\AnnotationException;
use Infrastructure\Events\RequestEvent;
use Infrastructure\Exceptions\InfrastructureException;
use Infrastructure\Exceptions\ValidationException;
use Infrastructure\Models\Validator;
use ReflectionException;
use Symfony\Component\Validator\Exception\ValidatorException;

class ExceptionSubscriber
{
    /**
     * @param RequestEvent $event
     * @throws AnnotationException
     * @throws InfrastructureException
     * @throws ValidationException
     * @throws ReflectionException
     * @throws ValidatorException
     */
    public function onRequest(RequestEvent $event)
    {
        $validator = new Validator($event->getController(), $event->getMethodName());
        $validator->validate(array_merge($event->getRequest()->request->all(), $event->getRequest()->query->all()));

        //TODO: add filter
        //$event->getRequest()->request->replace(['id' => '33', 'name' => 'RRR']);
    }
}