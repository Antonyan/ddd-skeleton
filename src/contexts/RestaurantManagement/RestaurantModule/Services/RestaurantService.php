<?php

namespace Contexts\RestaurantManagement\RestaurantModule\Services;

use Contexts\RestaurantManagement\RestaurantModule\Repositories\RestaurantDbRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Infrastructure\Exceptions\InfrastructureException;
use Infrastructure\Services\BaseService;
use ReflectionException;

class RestaurantService extends BaseService
{
    /**
     * @return ArrayCollection
     * @throws InfrastructureException
     * @throws ReflectionException
     */
    public function load() : ArrayCollection
    {
        return $this->gerRestaurantDbRepository()->load([]);
    }

    /**
     * @return RestaurantDbRepository
     * @throws InfrastructureException
     * @throws ReflectionException
     */
    public function gerRestaurantDbRepository() : RestaurantDbRepository
    {
        return $this->container()->get('restaurantDbRepository');
    }
}