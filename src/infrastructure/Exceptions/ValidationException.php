<?php
namespace Infrastructure\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class ValidationException extends InternalException
{
    /**
     * ValidationException constructor.
     * @param array $message
     */
    public function __construct(array $message)
    {
        parent::__construct(json_encode($message), Response::HTTP_BAD_REQUEST);
    }
}