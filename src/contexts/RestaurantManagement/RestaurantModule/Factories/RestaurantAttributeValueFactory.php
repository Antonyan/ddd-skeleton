<?php

namespace Contexts\RestaurantManagement\RestaurantModule\Factories;

use Contexts\RestaurantManagement\RestaurantModule\Models\Restaurant;
use Contexts\RestaurantManagement\RestaurantModule\Models\RestaurantAttributeValue;
use Infrastructure\Services\EntityFactory;

class RestaurantAttributeValueFactory extends EntityFactory
{
    /**
     * @param array $data
     * @return RestaurantAttributeValue
     */
    public function create(array $data) : RestaurantAttributeValue
    {
        return (new RestaurantAttributeValue())
            ->setId(array_key_exists('id', $data) ? $data['id'] : 0)
            ->setRestaurant((new Restaurant())->setId($data['restaurant'])->setName(''))
            ->setValue($data['value']);
    }
}