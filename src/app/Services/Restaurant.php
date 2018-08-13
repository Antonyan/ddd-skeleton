<?php

namespace App\Services;

use Contexts\RestaurantManagement\RestaurantManagementContract;
use Exception;
use Infrastructure\Annotations\Validation;
use Infrastructure\Models\CreateEntityJsonResponse;
use Infrastructure\Models\DeleteEntityJsonResponse;
use Infrastructure\Models\GetEntityJsonResponse;
use Infrastructure\Models\LoadCollectionJsonResponse;
use Infrastructure\Services\BaseService;
use Symfony\Component\HttpFoundation\Request;

class Restaurant extends BaseService
{
    /**
     * @return LoadCollectionJsonResponse
     * @throws Exception
     */
    public function load() : LoadCollectionJsonResponse
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
     * @Validation(name="name", type="string", required=true, minLength=3)
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