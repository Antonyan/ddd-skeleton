<?php

$I = new ApiTester($scenario);

$I->haveInDatabase('restaurants', ['id' => 200, 'name' => 'getRestaurant']);
$I->haveInDatabase('restaurantAttributeValues', ['id' => 210, 'restaurantId' => 200, 'value' => 'one']);

$I->setHeader('Content-type', 'application/json');
$I->sendDELETE('restaurants/200');

$I->canSeeResponseCodeIs(204);

$I->cantSeeInDatabase('restaurants', ['id' => 200]);
$I->cantSeeInDatabase('restaurantAttributeValues', ['id' => 210]);