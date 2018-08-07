<?php

namespace Contexts\RestaurantManagement\RestaurantAttributeValueModule\Models;

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
     * @ManyToOne(targetEntity="Contexts\RestaurantManagement\RestaurantModule\Models\Restaurant", inversedBy="attributes")
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
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getRestaurant()
    {
        return $this->restaurant;
    }

    /**
     * @param mixed $restaurant
     */
    public function setRestaurant($restaurant): void
    {
        $this->restaurant = $restaurant;
    }
}


