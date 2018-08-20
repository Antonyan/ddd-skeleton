<?php

use App\Services\Restaurant;
use Infrastructure\Models\Routing\RouteBuilder;
use Infrastructure\Models\Routing\RouteCollectionBuilder;
use Symfony\Component\Routing;

$routesCollectionBuilder = new RouteCollectionBuilder();

$routesCollectionBuilder->addCRUD('/restaurants', Restaurant::class);

return $routesCollectionBuilder->build();