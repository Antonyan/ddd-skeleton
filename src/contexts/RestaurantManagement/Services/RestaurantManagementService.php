<?php

namespace Contexts\RestaurantManagement\Services;

use Contexts\RestaurantManagement\RestaurantModule\Services\RestaurantService;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Infrastructure\Exceptions\InfrastructureException;
use Infrastructure\Services\BaseService;
use ReflectionException;

class RestaurantManagementService extends BaseService
{
    /**
     * @return ArrayCollection
     * @throws InfrastructureException
     * @throws ReflectionException
     */
    public function loadRestaurants() : ArrayCollection
    {
        return $this->getRestaurantService()->load();
    }

    /**
     * @return RestaurantService
     * @throws Exception
     */
    public function getRestaurantService() : RestaurantService
    {
        return $this->container()->get('RestaurantService');
    }
}