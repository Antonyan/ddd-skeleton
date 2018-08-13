<?php

namespace Contexts\RestaurantManagement\RestaurantModule\Repositories;

use Contexts\RestaurantManagement\RestaurantModule\Factories\RestaurantAttributeValueFactory;
use Contexts\RestaurantManagement\RestaurantModule\Models\RestaurantAttributeValue;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Infrastructure\Repository\DbRepository;

class RestaurantAttributeValueDbRepository extends DbRepository
{
    /**
     * @var RestaurantAttributeValueFactory
     */
    private $attributeValueFactory;

    public function __construct(EntityManager $entityManager, $entity, RestaurantAttributeValueFactory $attributeValueBuilder)
    {
        parent::__construct($entityManager, $entity);
        $this->attributeValueFactory = $attributeValueBuilder;
    }

    /**
     * @param array $data
     * @return RestaurantAttributeValue
     */
    protected function createObject(array $data) : RestaurantAttributeValue
    {
        return $this->attributeValueFactory->create($data);
    }

    /**
     * @param array $data
     * @return RestaurantAttributeValue
     * @throws Exception
     */
    public function create(array $data) : RestaurantAttributeValue
    {
        return $this->createEntity($data);
    }

    /**
     * @param array $data
     * @return RestaurantAttributeValue
     * @throws Exception
     */
    public function update(array $data) : RestaurantAttributeValue
    {
        return $this->updateEntity($data);
    }

    /**
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function delete(int $id) : bool
    {
        return $this->deleteEntity($id, RestaurantAttributeValue::class);
    }
}