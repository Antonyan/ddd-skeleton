<?php

namespace Contexts\RestaurantManagement\RestaurantModule\Builders;

use Contexts\RestaurantManagement\RestaurantModule\Models\Restaurant;
use Contexts\RestaurantManagement\RestaurantModule\Models\RestaurantAttributeValue;
use Infrastructure\Services\BaseBuilder;

class RestaurantAttributeValueBuilder extends BaseBuilder
{
    /**
     * @param array $data
     * @return RestaurantAttributeValue
     */
    public function build(array $data) : RestaurantAttributeValue
    {
        return (new RestaurantAttributeValue())
            ->setId(array_key_exists('id', $data) ? $data['id'] : 0)
            ->setRestaurant((new Restaurant())->setId($data['restaurant'])->setName(''))
            ->setValue($data['value']);
    }
}