<?php

namespace Contexts\RestaurantManagement\Services;

use Contexts\RestaurantManagement\RestaurantModule\Services\RestaurantService;
use Exception;
use Persistence\Services\BaseService;

class RestaurantManagementService extends BaseService
{
    public function loadRestaurants()
    {
        return $this->getRestaurantService()->load();
    }

    /**
     * @return RestaurantService
     * @throws Exception
     */
    public function getRestaurantService() : RestaurantService
    {
        return $this->container()->get('RestaurantService');
    }
}