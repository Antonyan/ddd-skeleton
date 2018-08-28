<?php

use Contexts\RestaurantManagement\RestaurantModule\Factories\RestaurantAttributeValueFactory;
use Contexts\RestaurantManagement\RestaurantModule\Factories\RestaurantFactory;
use Contexts\RestaurantManagement\RestaurantModule\Mappers\RestaurantAttributeValueDbMapper;
use Contexts\RestaurantManagement\RestaurantModule\Mappers\RestaurantDbMapper;
use Contexts\RestaurantManagement\RestaurantModule\Models\RestaurantAttributeValue;
use Symfony\Component\DependencyInjection\Reference;

$containerBuilder->register('restaurantAttributeValueFactory', RestaurantAttributeValueFactory::class);

$containerBuilder->register('restaurantDbMapper', RestaurantDbMapper::class)
    ->addArgument(new Reference('entityManager'))
    ->addArgument(RestaurantAttributeValue::class)
    ->addArgument(new Reference('restaurantAttributeValueBuilder'));

$containerBuilder->register('restaurantFactory', RestaurantFactory::class);

$containerBuilder->register('restaurantDbMapper', RestaurantDbMapper::class)
    ->addArgument(new Reference('restaurantFactory'))
    ->addArgument($containerBuilder->get('config')->RestaurantDbMapper)
    ->addArgument(new Reference('MySqlClient'));

$containerBuilder->register('restaurantAttributeValueDbMapper', RestaurantAttributeValueDbMapper::class)
    ->addArgument(new Reference('restaurantAttributeValueFactory'))
    ->addArgument($containerBuilder->get('config')->RestaurantAttributeValueDbMapper)
    ->addArgument(new Reference('MySqlClient'));





