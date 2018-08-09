<?php

namespace Contexts\RestaurantManagement\RestaurantModule\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @Entity @Table(name="restaurants")
 */
class Restaurant
{
    /** @Id @Column(type="integer") @GeneratedValue */
    private $id;

    /** @Column(type="string") */
    private $name;

    /**
     * @OneToMany(targetEntity="Contexts\RestaurantManagement\RestaurantModule\Models\RestaurantAttributeValue", mappedBy="restaurant", orphanRemoval=true)
     * @var Collection
     */
    private $attributes = null;

    public function __construct()
    {
        $this->attributes = new ArrayCollection();
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
     * @return Restaurant
     */
    public function setId($id): Restaurant
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return Restaurant
     */
    public function setName($name): Restaurant
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getAttributes() : Collection
    {
        return $this->attributes;
    }

    /**
     * @param Collection $attributes
     * @return Restaurant
     */
    public function setAttributes(Collection $attributes): Restaurant
    {
        $this->attributes = $attributes;
        return $this;
    }
}
