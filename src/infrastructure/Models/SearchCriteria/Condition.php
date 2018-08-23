<?php

namespace Infrastructure\Models\SearchCriteria;

interface Condition
{
    public function toCondition() : array;
    public function type() : string;
}