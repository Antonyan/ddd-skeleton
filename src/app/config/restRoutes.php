<?php

use App\Services\Restaurant;
use Symfony\Component\Routing;

$routes = new Routing\RouteCollection();

$routes->add('loadRestaurants', new Routing\Route('/restaurants', [
    '_controller' => Restaurant::class . '::load'], [], [], '', [], ['GET']));

$routes->add('createRestaurant', new Routing\Route('/restaurants', [
    '_controller' => Restaurant::class . '::create'], [], [], '', [], ['POST']));

$routes->add('getRestaurant', new Routing\Route('/restaurants/{id}', [
    '_controller' => Restaurant::class . '::get'], [], [], '', [], ['GET']));


return $routes;