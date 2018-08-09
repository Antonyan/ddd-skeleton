<?php

namespace Contexts\RestaurantManagement\RestaurantModule\Models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @Entity() @Table(name="restaurantAttributeValues")
 */
class RestaurantAttributeValue
{
    /** @Id @Column(type="integer") @GeneratedValue(strategy="AUTO")  */
    private $id;

    /** @Column(type="string") */
    private $value;

    /**
     * @ManyToOne(targetEntity="Contexts\RestaurantManagement\RestaurantModule\Models\Restaurant", inversedBy="attributes", fetch="EAGER")
     */
    private $restaurant;

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
    public function getRestaurant()
    {
        return $this->restaurant;
    }

    /**
     * @param $restaurant
     * @return RestaurantAttributeValue
     */
    public function setRestaurant($restaurant): RestaurantAttributeValue
    {
        $this->restaurant = $restaurant;
        return $this;
    }
}


