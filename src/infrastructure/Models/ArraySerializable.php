<?php

namespace Infrastructure\Models;

interface ArraySerializable
{
    /**
     * @return array
     */
    public function toArray() : array;
}
