<?php

use Contexts\RestaurantManagement\RestaurantModule\Factories\RestaurantAttributeValueFactory;
use Contexts\RestaurantManagement\RestaurantModule\Factories\RestaurantFactory;
use Contexts\RestaurantManagement\RestaurantModule\Mappers\RestaurantAttributeValueDbMapper;
use Contexts\RestaurantManagement\RestaurantModule\Mappers\RestaurantDbMapper;
use Contexts\RestaurantManagement\RestaurantModule\Models\Restaurant;
use Contexts\RestaurantManagement\RestaurantModule\Models\RestaurantAttributeValue;
use Contexts\RestaurantManagement\RestaurantModule\Repositories\RestaurantAttributeValueDbRepository;
use Contexts\RestaurantManagement\RestaurantModule\Repositories\RestaurantDbRepository;
use Symfony\Component\DependencyInjection\Reference;

$containerBuilder->register('restaurantDbRepository', RestaurantDbRepository::class)
    ->setArgument('$entityRepository', new Reference('entityManager'))
    ->setArgument('$entity', Restaurant::class);

$containerBuilder->register('restaurantAttributeValueBuilder', RestaurantAttributeValueFactory::class);

$containerBuilder->register('restaurantAttributeValueDbRepository', RestaurantAttributeValueDbRepository::class)
    ->addArgument(new Reference('entityManager'))
    ->addArgument(RestaurantAttributeValue::class)
    ->addArgument(new Reference('restaurantAttributeValueBuilder'));

$containerBuilder->register('restaurantDbMapper', RestaurantDbMapper::class)
    ->addArgument(new Reference('entityManager'))
    ->addArgument(RestaurantAttributeValue::class)
    ->addArgument(new Reference('restaurantAttributeValueBuilder'));

$containerBuilder->register('restaurantFactory', RestaurantFactory::class);

$containerBuilder->register('restaurantDbMapper', RestaurantDbMapper::class)
    ->addArgument(new Reference('db'))
    ->addArgument(new Reference('restaurantFactory'))
    ->addArgument($containerBuilder->get('config')->RestaurantDbMapper);

$containerBuilder->register('restaurantAttributeValueDbMapper', RestaurantAttributeValueDbMapper::class)
    ->addArgument(new Reference('db'))
    ->addArgument(new Reference('restaurantAttributeValueBuilder'))
    ->addArgument($containerBuilder->get('config')->RestaurantAttributeValueDbMapper);




