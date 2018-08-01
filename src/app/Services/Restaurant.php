<?php

namespace App\Services;

use Contexts\RestaurantManagement\Services\RestaurantManagementService;
use Exception;
use Infrastructure\Services\BaseService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;

class Restaurant extends BaseService
{
    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function load(Request $request) : Response
    {
        /** @var Serializer $serializer */
        $serializer = $this->container()->get('serializer');
        return new Response($serializer->serialize($this->getRestaurantManagement()->loadRestaurants(), 'json'));
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