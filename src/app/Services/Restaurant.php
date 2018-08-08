<?php

namespace App\Services;

use Contexts\RestaurantManagement\Services\RestaurantManagementService;
use Exception;
use Infrastructure\Annotations\Validation;
use Infrastructure\Models\CreateEntityJsonResponse;
use Infrastructure\Models\GetEntityJsonResponse;
use Infrastructure\Models\LoadCollectionJsonResponse;
use Infrastructure\Services\BaseService;
use Symfony\Component\HttpFoundation\Request;

class Restaurant extends BaseService
{
    /**
     * @param Request $request
     * @return LoadCollectionJsonResponse
     * @throws Exception
     */
    public function load(Request $request) : LoadCollectionJsonResponse
    {
        return new LoadCollectionJsonResponse($this->getRestaurantManagement()->loadRestaurants());
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
     * @param $id
     * @return GetEntityJsonResponse
     * @throws Exception
     */
    public function get($id) : GetEntityJsonResponse
    {
        return new GetEntityJsonResponse($this->getRestaurantManagement()->get($id));
    }

    /**
     * @return RestaurantManagementService
     * @throws Exception
     */
    private function getRestaurantManagement() : RestaurantManagementService
    {
        return $this->container()->get('restaurantManagement');
    }
}