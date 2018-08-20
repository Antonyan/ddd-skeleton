<?php

use Contexts\RestaurantManagement\Services\RestaurantManagementService;
use Symfony\Component\DependencyInjection\ContainerBuilder;

$containerBuilder->register('restaurantManagement', RestaurantManagementService::class);


