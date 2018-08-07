<?php

use Contexts\RestaurantManagement\RestaurantModule\Models\Restaurant;
use Symfony\Component\DependencyInjection\Reference;

$containerBuilder->register('restaurantAttributeValueDbRepository', DbRepository::class)
    ->setArgument('$entityRepository', new Reference('entityManager'))
    ->setArgument('$entity', Restaurant::class);