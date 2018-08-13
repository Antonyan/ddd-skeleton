<?php

namespace Infrastructure\Models;


class LoadCollectionJsonResponse extends BaseJsonResponse
{
    /**
     * @return int
     */
    protected function statusCode(): int
    {
        return self::HTTP_OK;
    }
}