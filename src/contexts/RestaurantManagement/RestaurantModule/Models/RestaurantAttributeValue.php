<?php

namespace Contexts\RestaurantManagement\RestaurantModule\Models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Infrastructure\Models\ArraySerializable;

/**
 * @Entity() @Table(name="restaurantAttributeValues")
 */
class RestaurantAttributeValue implements ArraySerializable
{
    /** @Id @Column(type="integer") @GeneratedValue(strategy="AUTO")  */
    private $id;

    /** @Column(type="string") */
    private $value;

    /**
     * @ManyToOne(targetEntity="Contexts\RestaurantManagement\RestaurantModule\Models\Restaurant", inversedBy="attributes", fetch="EAGER")
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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return RestaurantAttributeValue
     */
    public function setId($id): RestaurantAttributeValue
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param $value
     * @return RestaurantAttributeValue
     */
    public function setValue($value): RestaurantAttributeValue
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRestaurantId()
    {
        return $this->restaurantId;
    }

    /**
     * @param $restaurantId
     * @return RestaurantAttributeValue
     */
    public function setRestaurantId($restaurantId): RestaurantAttributeValue
    {
        $this->restaurantId = $restaurantId;
        return $this;
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


