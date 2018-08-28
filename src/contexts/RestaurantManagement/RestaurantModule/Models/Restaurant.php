<?php

namespace Contexts\RestaurantManagement\RestaurantModule\Models;

use Infrastructure\Models\ArraySerializable;

class Restaurant implements ArraySerializable
{
    public const ID = 'id';
    public const NAME = 'name';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * Restaurant constructor.
     * @param $id
     * @param $name
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
