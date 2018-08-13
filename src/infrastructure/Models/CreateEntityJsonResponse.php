<?php

namespace Infrastructure\Models;


class CreateEntityJsonResponse extends BaseJsonResponse
{
    /**
     * @return int
     */
    protected function statusCode(): int
    {
        return self::HTTP_CREATED;
    }
}