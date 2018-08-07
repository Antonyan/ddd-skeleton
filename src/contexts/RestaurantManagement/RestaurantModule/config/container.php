<?php

use Contexts\RestaurantManagement\RestaurantModule\Models\Restaurant;
use Contexts\RestaurantManagement\RestaurantModule\Repositories\RestaurantDbRepository;
use Symfony\Component\DependencyInjection\Reference;

$containerBuilder->register('restaurantDbRepository', RestaurantDbRepository::class)
    ->setArgument('$entityRepository', new Reference('entityManager'))
    ->setArgument('$entity', Restaurant::class);