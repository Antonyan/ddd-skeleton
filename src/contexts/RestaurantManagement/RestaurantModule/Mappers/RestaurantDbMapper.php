<?php

namespace Contexts\RestaurantManagement\RestaurantModule\Mappers;

use Contexts\RestaurantManagement\RestaurantModule\Models\Restaurant;
use Infrastructure\Exceptions\InfrastructureException;
use Infrastructure\Mappers\DbMapper;

class RestaurantDbMapper extends DbMapper
{
    /**
     * @param array $objectData
     * @return Restaurant
     * @throws InfrastructureException
     */
    public function create(array $objectData) : Restaurant
    {
        return parent::create($objectData);
    }

    /**
     * @param array $objectData
     * @return Restaurant
     * @throws InfrastructureException
     */
    public function update(array $objectData) : Restaurant
    {
        return parent::update($objectData);
    }
}