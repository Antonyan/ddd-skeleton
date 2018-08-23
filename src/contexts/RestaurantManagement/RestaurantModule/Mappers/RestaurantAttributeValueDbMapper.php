<?php

namespace Contexts\RestaurantManagement\RestaurantModule\Mappers;

use Contexts\RestaurantManagement\RestaurantModule\Models\RestaurantAttributeValue;
use Infrastructure\Exceptions\InfrastructureException;
use Infrastructure\Mappers\DbMapper;

class RestaurantAttributeValueDbMapper extends DbMapper
{
    /**
     * @param array $objectData
     * @return RestaurantAttributeValue
     * @throws InfrastructureException
     */
    public function create(array $objectData) : RestaurantAttributeValue
    {
        return parent::create($objectData);
    }

    /**
     * @param array $objectData
     * @return RestaurantAttributeValue
     * @throws InfrastructureException
     */
    public function update(array $objectData) : RestaurantAttributeValue
    {
        return parent::update($objectData);
    }
}