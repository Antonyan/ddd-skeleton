<?php

$I = new ApiTester($scenario);

$I->haveInDatabase('restaurants', ['id' => 100, 'name' => 'getRestaurant']);
$I->haveInDatabase('restaurantAttributeValues', ['restaurant_id' => 100, 'value' => 'one']);

$I->setHeader('Content-type', 'application/json');
$I->sendGET('restaurants/100');

$I->seeResponseContainsJson(['name' => 'getRestaurant']);
