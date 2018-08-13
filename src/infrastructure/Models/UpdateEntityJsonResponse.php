<?php

namespace Infrastructure\Models;


class UpdateEntityJsonResponse extends BaseJsonResponse
{
    /**
     * @return int
     */
    protected function statusCode(): int
    {
        return self::HTTP_OK;
    }
}