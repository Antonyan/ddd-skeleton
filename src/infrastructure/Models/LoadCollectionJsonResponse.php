<?php

namespace Infrastructure\Models;

use Symfony\Component\HttpFoundation\JsonResponse;

class LoadCollectionJsonResponse extends JsonResponse
{
    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}