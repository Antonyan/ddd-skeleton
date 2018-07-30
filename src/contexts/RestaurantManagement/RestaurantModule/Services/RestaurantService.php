<?php

namespace Contexts\RestaurantManagement\RestaurantModule\Services;

use Infrastructure\Exceptions\InfrastructureException;
use Infrastructure\Services\BaseService;
use ReflectionException;

class RestaurantService extends BaseService
{
    /**
     * @return mixed
     * @throws InfrastructureException
     * @throws ReflectionException
     */
    public function load()
    {
        return $this->config()->restaurants();
    }
}