<?php

namespace App\Services;

use Contexts\RestaurantManagement\RestaurantManagementContract;
use Exception;
use Infrastructure\Annotations\Validation;
use Infrastructure\Models\CreateEntityJsonResponse;
use Infrastructure\Models\DeleteEntityJsonResponse;
use Infrastructure\Models\GetEntityJsonResponse;
use Infrastructure\Models\SearchCriteria\SearchCriteriaQueryString;
use Infrastructure\Services\BaseService;
use Infrastructure\Services\FilterBuilder;
use Symfony\Component\HttpFoundation\Request;

class Restaurant extends BaseService
{
    /**
     * @Validation(name="id", type="string")
     * @Validation(name="name", type="string")
     * @Validation(name="limit", type="string")
     * @Validation(name="offset", type="string")
     * @Validation(name="orderByAsc", type="string")
     * @Validation(name="orderByDesc", type="string")
     * @param Request $request
     * @return GetEntityJsonResponse
     * @throws Exception
     */
    public function load(Request $request) : GetEntityJsonResponse
    {
            return new GetEntityJsonResponse($this->getRestaurantManagement()
            ->loadRestaurants(new SearchCriteriaQueryString($request->query->all()))->toArray());
    }

    /**
     * @Validation(name="name", type="string", required=true, minLength=3)
     * @param Request $request
     * @return CreateEntityJsonResponse
     * @throws Exception
     */
    public function create(Request $request) : CreateEntityJsonResponse
    {
        return new CreateEntityJsonResponse($this->getRestaurantManagement()->create($request->request->all()));
    }

    /**
     * @Validation(name="name", type="string", required=true, minLength=3)
     * @Validation(name="attributes", type="array")
     * @param Request $request
     * @param $id
     * @return CreateEntityJsonResponse
     * @throws Exception
     */
    public function update(Request $request, $id) : CreateEntityJsonResponse
    {
        return new CreateEntityJsonResponse($this->getRestaurantManagement()->update($id, $request->request->all()));
    }

    /**
     * @param $id
     * @return DeleteEntityJsonResponse
     * @throws Exception
     */
    public function delete($id) : DeleteEntityJsonResponse
    {
        $this->getRestaurantManagement()->delete($id);
        return new DeleteEntityJsonResponse();
    }

    /**
     * @param $id
     * @return GetEntityJsonResponse
     * @throws Exception
     */
    public function get($id) : GetEntityJsonResponse
    {
        return new GetEntityJsonResponse($this->getRestaurantManagement()->get($id));
    }

    /**
     * @return RestaurantManagementContract
     * @throws Exception
     */
    private function getRestaurantManagement() : RestaurantManagementContract
    {
        return $this->container()->get('restaurantManagement');
    }
}