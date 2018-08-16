<?php
namespace App\Services;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\JsonResponse;

class Error
{
    public function handle(FlattenException $exception)
    {
        return new JsonResponse(['message' => $exception->getMessage()], $exception->getStatusCode(), $exception->getHeaders());
    }
}