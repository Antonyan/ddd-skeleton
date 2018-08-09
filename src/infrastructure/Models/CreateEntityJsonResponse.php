<?php

namespace Infrastructure\Models;


class CreateEntityJsonResponse extends BaseJsonResponse
{
    /**
     * @return int
     */
    protected function statusCode(): int
    {
        return BaseJsonResponse::HTTP_CREATED;
    }
}