<?php

namespace Infrastructure\Repository;

abstract class BaseRepository
{
    abstract protected function createObject(array $data);
}