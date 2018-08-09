<?php

namespace App\Services;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class Error
{
    public function handle(FlattenException $exception)
    {
        if ($exception->getClass() == ResourceNotFoundException::class) {
            $msg = 'Something went wrong! ('.$exception->getMessage().')';
            return new JsonResponse(
                ['message' => $msg],
                $exception->getStatusCode(),
                $exception->getHeaders()
            );
        }

        return new JsonResponse(['message' => $exception->getMessage()], $exception->getStatusCode(), $exception->getHeaders());
    }
}