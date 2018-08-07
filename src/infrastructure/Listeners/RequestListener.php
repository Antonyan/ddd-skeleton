<?php

namespace Infrastructure\Listeners;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Infrastructure\Events\RequestEvent;
use ReflectionClass;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Validation;

class RequestListener
{
    public function onRequest(RequestEvent $event)
    {

    }
}