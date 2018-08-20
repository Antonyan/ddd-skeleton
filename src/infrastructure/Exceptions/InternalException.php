<?php

namespace Infrastructure\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class InternalException extends \Exception implements HttpExceptionInterface
{
    /**
     * @var array
     */
    protected $headers;

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * BaseInternalException constructor.
     * @param string $message
     * @param int $statusCode
     * @param array $headers
     * @param null|Throwable $previous
     * @param int $code
     */
    public function __construct($message = '', $statusCode, $headers = [], Throwable $previous = null, $code = 0)
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}