<?php

namespace Infrastructure\Models;


class GetEntityJsonResponse extends BaseJsonResponse
{
    /**
     * @return int
     */
    protected function statusCode(): int
    {
        return self::HTTP_OK;
    }
}