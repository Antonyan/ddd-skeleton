<?php

namespace App\Services;

use InvalidArgumentException;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class Error
{
    /**
     * @param FlattenException $exception
     * @return Response
     * @throws InvalidArgumentException
     */
    public function exception(ResourceNotFoundException $exception)
    {
        $msg = 'Something went wrong! ('.$exception->getMessage().')';
        return new Response($msg, $exception->getStatusCode());
    }
}