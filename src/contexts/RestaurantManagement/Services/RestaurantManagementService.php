<?php

namespace Contexts\RestaurantManagement\Services;

use Contexts\RestaurantManagement\RestaurantModule\Models\Restaurant;
use Contexts\RestaurantManagement\RestaurantModule\Services\RestaurantService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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
     * @throws Exception
     */
    public function loadRestaurants() : ArrayCollection
    {
        return $this->getRestaurantService()->load();
    }

    /**
     * @param array $data
     * @return Restaurant
     * @throws InfrastructureException
     * @throws ReflectionException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function create(array $data) : Restaurant
    {
        return $this->getRestaurantService()->create($data);
    }

    /**
     * @param $id
     * @return Restaurant
     * @throws Exception
     */
    public function get($id) : Restaurant
    {
        return $this->getRestaurantService()->get($id);
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