<?php

namespace Infrastructure\Repository;

abstract class BaseRepository
{
    abstract protected function createObject(array $data);

    abstract public function create(array $data);

    abstract public function update(array $data);

    abstract public function delete(int $id) : bool;
}