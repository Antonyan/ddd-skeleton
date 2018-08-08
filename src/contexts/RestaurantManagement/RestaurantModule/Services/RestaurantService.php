<?php

namespace Contexts\RestaurantManagement\RestaurantModule\Services;

use Contexts\RestaurantManagement\RestaurantModule\Repositories\RestaurantDbRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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
        return $this->getRestaurantDbRepository()->load([]);
    }

    /**
     * @param array $data
     * @return mixed
     * @throws InfrastructureException
     * @throws ReflectionException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(array $data)
    {
        $data['id'] = 0;
        return $this->getRestaurantDbRepository()->create($data);
    }

    public function get($id)
    {
        return $this->getRestaurantDbRepository()->get($id);
    }

    /**
     * @return RestaurantDbRepository
     * @throws InfrastructureException
     * @throws ReflectionException
     */
    public function getRestaurantDbRepository() : RestaurantDbRepository
    {
        return $this->container()->get('restaurantDbRepository');
    }
}