<?php

namespace Contexts\RestaurantManagement\RestaurantModule\Services;

use Contexts\RestaurantManagement\RestaurantModule\Models\Restaurant;
use Contexts\RestaurantManagement\RestaurantModule\Repositories\RestaurantAttributeValueDbRepository;
use Contexts\RestaurantManagement\RestaurantModule\Repositories\RestaurantDbRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
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
     * @return Restaurant
     * @throws InfrastructureException
     * @throws ReflectionException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(array $data) : Restaurant
    {
        $data['id'] = 0;
        return $this->getRestaurantDbRepository()->create($data);
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed
     * @throws InfrastructureException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     * @throws ORMInvalidArgumentException
     */
    public function update($id, array $data)
    {
        if (array_key_exists('attributes', $data) && !empty($data['attributes'])){
            $this->getRestaurantAttributeValueDbRepository()
                ->batchUpdate($data['attributes']);
        }

        $this->getRestaurantDbRepository()->update(array_merge(['id' => $id], $data));

        return $this->get($id);
    }

    /**
     * @param $id
     * @return bool
     * @throws InfrastructureException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     * @throws ORMInvalidArgumentException
     */
    public function delete($id) : bool
    {
        return $this->getRestaurantDbRepository()->delete($id, Restaurant::class);
    }

    /**
     * @param $id
     * @return Restaurant
     * @throws InfrastructureException
     * @throws ReflectionException
     */
    public function get($id) : Restaurant
    {
        return $this->getRestaurantDbRepository()->get($id);
    }

    /**
     * @return RestaurantDbRepository
     * @throws InfrastructureException
     * @throws ReflectionException
     */
    private function getRestaurantDbRepository() : RestaurantDbRepository
    {
        return $this->container()->get('restaurantDbRepository');
    }

    /**
     * @return RestaurantAttributeValueDbRepository
     * @throws InfrastructureException
     * @throws ReflectionException
     */
    private function getRestaurantAttributeValueDbRepository() : RestaurantAttributeValueDbRepository
    {
        return $this->container()->get('restaurantAttributeValueDbRepository');
    }
}