<?php

namespace App\Services;

use Contexts\RestaurantManagement\Services\RestaurantManagementService;
use Exception;
use Persistence\Services\BaseService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Restaurant extends BaseService
{
    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function load(Request $request)
    {
        return new Response(json_encode($this->getRestaurantManagement()->loadRestaurants(), JSON_FORCE_OBJECT));
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