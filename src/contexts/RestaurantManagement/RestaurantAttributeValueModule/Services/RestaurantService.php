<?php

namespace Contexts\RestaurantManagement\RestaurantAttributeValueModule\Services;

use Contexts\RestaurantManagement\RestaurantModule\Repositories\RestaurantDbRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Infrastructure\Exceptions\InfrastructureException;
use Infrastructure\Repository\DbRepository;
use Infrastructure\Services\BaseService;
use ReflectionException;

class RestaurantAttributeValueService extends BaseService
{
    /**
     * @return ArrayCollection
     * @throws InfrastructureException
     * @throws ReflectionException
     */
    public function load() : ArrayCollection
    {
        return $this->gerRestaurantAttributeValueDbRepository()->load([]);
    }

    /**
     * @return RestaurantDbRepository
     * @throws InfrastructureException
     * @throws ReflectionException
     */
    public function gerRestaurantAttributeValueDbRepository() : DbRepository
    {
        return $this->container()->get('restaurantAttributeValueDbRepository');
    }
}