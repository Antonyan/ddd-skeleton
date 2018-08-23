<?php

namespace Infrastructure\Models;


use Symfony\Component\HttpFoundation\JsonResponse;

class UpdateEntityJsonResponse extends JsonResponse
{
    public function __construct(array $data = null)
    {
        parent::__construct($data, self::HTTP_OK);
    }
}