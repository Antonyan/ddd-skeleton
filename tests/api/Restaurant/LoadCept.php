<?php

$I = new ApiTester($scenario);

$restaurant = ['id' => '1', 'name' => 'Restaurant'];
$attributeOne = ['id' => '1', 'value' => 'one', 'restaurant_id' => '1'];
$attributeTwo = ['id' => '2', 'value' => 'two', 'restaurant_id' => '1'];

$I->haveInDatabase('restaurants', $restaurant);
$I->haveInDatabase('restaurantAttributeValues', $attributeOne);
$I->haveInDatabase('restaurantAttributeValues', $attributeTwo);

$I->sendGET('restaurants');

$I->seeResponseContainsJson($restaurant);