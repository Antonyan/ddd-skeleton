<?php

namespace Infrastructure\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

class InfrastructureException extends InternalException
{
    /**
     * InfrastructureException constructor.
     * @param string $message
     * @param null|Throwable $previous
     */
    public function __construct($message, Throwable $previous = null)
    {
        parent::__construct($message, Response::HTTP_INTERNAL_SERVER_ERROR, [], $previous);
    }
}