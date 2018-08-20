<?php
namespace Infrastructure\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

class HttpResourceNotFoundException extends InternalException
{
    /**
     * HttpResourceNotFoundException constructor.
     * @param string $message
     * @param null|Throwable $previous
     */
    public function __construct($message, Throwable $previous = null)
    {
        parent::__construct($message, Response::HTTP_NOT_FOUND, [], $previous);
    }
}