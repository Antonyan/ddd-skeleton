<?php

namespace Contexts\RestaurantManagement\RestaurantModule\Models;

use Infrastructure\Models\ArraySerializable;

class RestaurantAttributeValue implements ArraySerializable
{
    public const ID = 'id';
    public const VALUE = 'value';
    public const RESTAURANT_ID = 'restaurantId';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $value;

    /**
     * @var int
     */
    private $restaurantId;

    /**
     * RestaurantAttributeValue constructor.
     * @param $id
     * @param $value
     * @param $restaurant
     */
    public function __construct($id, $value, $restaurant)
    {
        $this->id = $id;
        $this->value = $value;
        $this->restaurantId = $restaurant;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'restaurantId' => $this->restaurantId
        ];
    }
}


