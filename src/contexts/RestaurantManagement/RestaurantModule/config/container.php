<?php

use Contexts\RestaurantManagement\RestaurantModule\Factories\RestaurantAttributeValueFactory;
use Contexts\RestaurantManagement\RestaurantModule\Factories\RestaurantFactory;
use Contexts\RestaurantManagement\RestaurantModule\Mappers\RestaurantAttributeValueDbMapper;
use Contexts\RestaurantManagement\RestaurantModule\Mappers\RestaurantDbMapper;
use Contexts\RestaurantManagement\RestaurantModule\Models\RestaurantAttributeValue;
use Infrastructure\Models\EntityToDataSourceTranslator;
use Symfony\Component\DependencyInjection\Reference;

$containerBuilder->register('restaurantAttributeValueFactory', RestaurantAttributeValueFactory::class);

$containerBuilder->register('restaurantDbMapper', RestaurantDbMapper::class)
    ->addArgument(new Reference('entityManager'))
    ->addArgument(RestaurantAttributeValue::class)
    ->addArgument(new Reference('restaurantAttributeValueBuilder'));

$containerBuilder->register('restaurantFactory', RestaurantFactory::class);

$containerBuilder->register('restaurantDbTranslator', EntityToDataSourceTranslator::class)
    ->addArgument($containerBuilder->get('config')->RestaurantDbTranslator);

$containerBuilder->register('restaurantAttributeValueDbTranslator', EntityToDataSourceTranslator::class)
    ->addArgument($containerBuilder->get('config')->RestaurantAttributeValueDbTranslator);

$containerBuilder->register('restaurantDbMapper', RestaurantDbMapper::class)
    ->addArgument(new Reference('restaurantFactory'))
    ->addArgument(new Reference('restaurantDbTranslator'))
    ->addArgument(new Reference('MySqlClient'));

$containerBuilder->register('restaurantAttributeValueDbMapper', RestaurantAttributeValueDbMapper::class)
    ->addArgument(new Reference('restaurantAttributeValueFactory'))
    ->addArgument(new Reference('restaurantAttributeValueDbTranslator'))
    ->addArgument(new Reference('MySqlClient'));



