<?php

namespace Contexts\RestaurantManagement\RestaurantModule\Services;

use Contexts\RestaurantManagement\RestaurantModule\Mappers\RestaurantAttributeValueDbMapper;
use Contexts\RestaurantManagement\RestaurantModule\Mappers\RestaurantDbMapper;
use Contexts\RestaurantManagement\RestaurantModule\Models\Restaurant;
use Contexts\RestaurantManagement\RestaurantModule\Models\RestaurantAttributeValue;
use Exception;
use Infrastructure\Exceptions\InfrastructureException;
use Infrastructure\Models\PaginationCollection;
use Infrastructure\Models\SearchCriteria\EqualCriteria;
use Infrastructure\Models\SearchCriteria\SearchCriteria;
use Infrastructure\Models\SearchCriteria\SearchCriteriaConstructor;
use Infrastructure\Services\BaseService;
use ReflectionException;

class RestaurantService extends BaseService
{
    /**
     * @param SearchCriteria $conditions
     * @return PaginationCollection
     * @throws InfrastructureException
     * @throws ReflectionException
     */
    public function load(SearchCriteria $conditions) : PaginationCollection
    {
        return $this->getRestaurantDbMapper()->load($conditions);
    }

    /**
     * @param array $data
     * @return Restaurant
     * @throws InfrastructureException
     * @throws ReflectionException
     * @throws Exception
     */
    public function create(array $data) : Restaurant
    {
        $data['id'] = 0;
        return $this->getRestaurantDbMapper()->create($data);
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function update($id, array $data) : Restaurant
    {
        //TODO: create batch update
        if (array_key_exists('attributes', $data) && !empty($data['attributes'])){
            foreach ($data['attributes'] as $attribute) {
                $this->getRestaurantAttributeValueDbMapper()->update($attribute);
            }
        }

        $this->getRestaurantDbMapper()->update(array_merge(['id' => $id], $data));

        return $this->get($id);
    }

    /**
     * @param $id
     * @return bool
     * @throws InfrastructureException
     * @throws Exception
     */
    public function delete($id) : bool
    {
        $this->getRestaurantAttributeValueDbMapper()
            ->delete(new SearchCriteriaConstructor([new EqualCriteria(RestaurantAttributeValue::RESTAURANT_ID, $id)]));
        return $this->getRestaurantDbMapper()->delete(new SearchCriteriaConstructor([new EqualCriteria('id', $id)]));
    }

    /**
     * @param $id
     * @return Restaurant
     * @throws InfrastructureException
     * @throws ReflectionException
     */
    public function get($id) : Restaurant
    {
        return $this->getRestaurantDbMapper()->get(['id' => $id]);
    }

    /**
     * @return RestaurantDbMapper
     * @throws InfrastructureException
     * @throws ReflectionException
     */
    private function getRestaurantDbMapper() : RestaurantDbMapper
    {
        return $this->container()->get('restaurantDbMapper');
    }

    /**
     * @return RestaurantAttributeValueDbMapper
     * @throws InfrastructureException
     * @throws ReflectionException
     */
    private function getRestaurantAttributeValueDbMapper() : RestaurantAttributeValueDbMapper
    {
        return $this->container()->get('restaurantAttributeValueDbMapper');
    }
}