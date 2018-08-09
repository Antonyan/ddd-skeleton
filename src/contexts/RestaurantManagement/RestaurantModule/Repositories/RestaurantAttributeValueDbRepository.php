<?php

namespace Contexts\RestaurantManagement\RestaurantModule\Repositories;

use Contexts\RestaurantManagement\RestaurantModule\Builders\RestaurantAttributeValueBuilder;
use Contexts\RestaurantManagement\RestaurantModule\Models\RestaurantAttributeValue;
use Doctrine\ORM\EntityManager;
use Infrastructure\Repository\DbRepository;

class RestaurantAttributeValueDbRepository extends DbRepository
{
    /**
     * @var RestaurantAttributeValueBuilder
     */
    private $attributeValueBuilder;

    public function __construct(EntityManager $entityManager, $entity, RestaurantAttributeValueBuilder $attributeValueBuilder)
    {
        parent::__construct($entityManager, $entity);
        $this->attributeValueBuilder = $attributeValueBuilder;
    }

    /**
     * @param array $data
     * @return RestaurantAttributeValue
     */
    protected function createObject(array $data) : RestaurantAttributeValue
    {
        return $this->attributeValueBuilder->build($data);
    }
}