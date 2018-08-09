<?php

namespace Contexts\RestaurantManagement\Services;

use Contexts\RestaurantManagement\RestaurantManagementContract;
use Contexts\RestaurantManagement\RestaurantModule\Models\Restaurant;
use Contexts\RestaurantManagement\RestaurantModule\Services\RestaurantService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Infrastructure\Exceptions\InfrastructureException;
use Infrastructure\Services\BaseService;
use ReflectionException;

class RestaurantManagementService extends BaseService implements RestaurantManagementContract
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
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function update($id, array $data) : Restaurant
    {
        return $this->getRestaurantService()->update($id, $data);
    }

    /**
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function delete($id) : bool
    {
        return $this->getRestaurantService()->delete($id);
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
    private function getRestaurantService() : RestaurantService
    {
        return $this->container()->get('RestaurantService');
    }
}