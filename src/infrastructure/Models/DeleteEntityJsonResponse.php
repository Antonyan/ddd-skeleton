<?php

namespace Infrastructure\Models;

use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteEntityJsonResponse extends JsonResponse
{
    public function __construct()
    {
        parent::__construct([], self::HTTP_NO_CONTENT);
    }
}