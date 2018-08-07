<?php

namespace Contexts\RestaurantManagement\RestaurantModule\Repositories;

use Contexts\RestaurantManagement\RestaurantModule\Models\Restaurant;
use Infrastructure\Repository\DbRepository;

class RestaurantDbRepository extends DbRepository
{
    /**
     * @param array $data
     * @return Restaurant
     */
    protected function createObject(array $data) : Restaurant
    {
        return (new Restaurant())->setId($data['id'])->setName($data['name']);
    }

}