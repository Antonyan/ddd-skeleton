<?php

namespace Contexts\RestaurantManagement\RestaurantModule\Factories;

use Contexts\RestaurantManagement\RestaurantModule\Models\Restaurant;
use Contexts\RestaurantManagement\RestaurantModule\Models\RestaurantAttributeValue;
use Infrastructure\Models\ArraySerializable;
use Infrastructure\Services\BaseFactory;
use Infrastructure\Services\EntityFactory;

class RestaurantFactory extends BaseFactory
{
    /**
     * @param array $data
     * @return ArraySerializable
     */
    public function create(array $data) : ArraySerializable
    {
        return new Restaurant(
            $this->setDefaultIfNotExists('id', 0, $data),
            $data['name']
        );
    }
}