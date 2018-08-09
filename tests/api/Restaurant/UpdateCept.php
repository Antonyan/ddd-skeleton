<?php

$I = new ApiTester($scenario);

$I->haveInDatabase('restaurants', ['id' => 130, 'name' => 'getRestaurant']);
$I->haveInDatabase('restaurantAttributeValues', ['id' => 120, 'restaurant_id' => 130, 'value' => 'one']);

$I->setHeader('Content-type', 'application/json');
$I->sendPUT('restaurants/130', ['name' => 'updateRestaurant', 'attributes' => [['id' => 120, 'restaurant' => 130, 'value' => 'two']]]);

$I->canSeeInDatabase('restaurants', ['id' => 130, 'name' => 'updateRestaurant']);
$I->canSeeInDatabase('restaurantAttributeValues', ['id' => 120, 'value' => 'two']);