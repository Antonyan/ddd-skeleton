<?php

namespace Infrastructure\Models;

use Symfony\Component\HttpFoundation\JsonResponse;

class CreateEntityJsonResponse extends JsonResponse
{
    public function __construct(array $data)
    {
        parent::__construct($data, self::HTTP_CREATED);
    }
}