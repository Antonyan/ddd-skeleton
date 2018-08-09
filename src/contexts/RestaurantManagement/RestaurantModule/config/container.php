<?php

use Contexts\RestaurantManagement\RestaurantModule\Builders\RestaurantAttributeValueBuilder;
use Contexts\RestaurantManagement\RestaurantModule\Models\Restaurant;
use Contexts\RestaurantManagement\RestaurantModule\Models\RestaurantAttributeValue;
use Contexts\RestaurantManagement\RestaurantModule\Repositories\RestaurantAttributeValueDbRepository;
use Contexts\RestaurantManagement\RestaurantModule\Repositories\RestaurantDbRepository;
use Symfony\Component\DependencyInjection\Reference;

$containerBuilder->register('restaurantDbRepository', RestaurantDbRepository::class)
    ->setArgument('$entityRepository', new Reference('entityManager'))
    ->setArgument('$entity', Restaurant::class);

$containerBuilder->register('restaurantAttributeValueBuilder', RestaurantAttributeValueBuilder::class);

$containerBuilder->register('restaurantAttributeValueDbRepository', RestaurantAttributeValueDbRepository::class)
    ->setArgument('$entityRepository', new Reference('entityManager'))
    ->setArgument('$entity', RestaurantAttributeValue::class)
    ->setArgument('$attributeValueBuilder', new Reference('restaurantAttributeValueBuilder'));