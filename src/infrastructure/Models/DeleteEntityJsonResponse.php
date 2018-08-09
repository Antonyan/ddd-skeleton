<?php

namespace Infrastructure\Models;

class DeleteEntityJsonResponse extends BaseJsonResponse
{
    public function __construct()
    {
        parent::__construct('');
    }

    /**
     * @return int
     */
    protected function statusCode(): int
    {
        return BaseJsonResponse::HTTP_NO_CONTENT;
    }
}