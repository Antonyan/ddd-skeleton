<?php

namespace Infrastructure\Services;

abstract class EntityFactory
{
    abstract public function create(array $data);

    /**
     * @param $data
     * @param $key
     * @param $default
     * @return mixed
     */
    protected function setDefaultIfNotExists($data, $key, $default)
    {
        return array_key_exists($key, $data) && $data[$key] !== null ? $data[$key] : $default;
    }
}