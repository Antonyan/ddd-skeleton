<?php

$I = new ApiTester($scenario);

$firstId = $I->randomId();
$secondId = $I->randomId();

$restaurant = ['id' => $firstId, 'name' => 'Restaurant'];
$secondRestaurant = ['id' => $secondId, 'name' => 'RestaurantSecond'];
$attributeOne = ['id' => $I->randomId(), 'value' => 'one', 'restaurantId' => $firstId];
$attributeTwo = ['id' => $I->randomId(), 'value' => 'two', 'restaurantId' => $firstId];

$I->haveInDatabase('restaurants', $restaurant);
$I->haveInDatabase('restaurants', $secondRestaurant);
$I->haveInDatabase('restaurantAttributeValues', $attributeOne);
$I->haveInDatabase('restaurantAttributeValues', $attributeTwo);

$I->sendGET('restaurants', ['id' => $secondId]);

$I->seeResponseContainsJson(array_merge($secondRestaurant, ['attributes' => []]));