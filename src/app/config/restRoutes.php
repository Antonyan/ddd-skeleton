<?php

use App\Services\Restaurant;
use Symfony\Component\Routing;

$routes = new Routing\RouteCollection();

$routes->add('loadRestaurants', new Routing\Route('/restaurants', array(
    '_controller' => Restaurant::class . '::load',
)));

return $routes;