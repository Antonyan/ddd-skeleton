<?php

namespace Contexts\RestaurantManagement\RestaurantModule\Repositories;

use Contexts\RestaurantManagement\RestaurantModule\Models\Restaurant;
use Exception;
use Infrastructure\Repository\DbRepository;

class RestaurantDbRepository extends DbRepository
{
    /**
     * @param array $data
     * @return Restaurant
     */
    protected function createObject(array $data) : Restaurant
    {
        return (new Restaurant())
            ->setId($data['id'])
            ->setName($data['name']);
    }

    /**
     * @param $id
     * @return Restaurant
     */
    public function get($id) : Restaurant
    {
        return $this->getEntity($id);
    }

    /**
     * @param array $data
     * @return Restaurant
     * @throws Exception
     */
    public function create(array $data) : Restaurant
    {
        return $this->createEntity($data);
    }

    /**
     * @param array $data
     * @return Restaurant
     * @throws Exception
     */
    public function update(array $data) : Restaurant
    {
        return $this->updateEntity($data);
    }

    /**
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        return $this->deleteEntity($id, Restaurant::class);
    }
}