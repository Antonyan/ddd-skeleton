<?php
namespace Infrastructure\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class HttpResourceNotFoundException extends \Exception implements HttpExceptionInterface
{
    /**
     * @var array
     */
    private $headers;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * HttpResourceNotFoundException constructor.
     * @param string $message
     * @param array $headers
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message, array $headers = [], $code = 0, Throwable $previous = null)
    {
        $this->statusCode = Response::HTTP_NOT_FOUND;
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