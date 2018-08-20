<?php

namespace Infrastructure\Models\SearchCriteria;

interface SearchCriteria
{
    public function limit() : int;

    public function offset() : int;

    public function orderBy() : array;

    public function conditions() : array;
}