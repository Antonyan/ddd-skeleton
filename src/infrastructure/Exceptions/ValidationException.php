<?php

namespace Infrastructure\Exceptions;

use Exception;

class ValidationException extends Exception
{
    public function __construct(array $message, int $code = 0)
    {
        parent::__construct(json_encode($message), $code, null);
    }
}