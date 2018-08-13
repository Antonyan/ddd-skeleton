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
        return self::HTTP_NO_CONTENT;
    }
}