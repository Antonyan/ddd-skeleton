<?php

$I = new ApiTester($scenario);

$restaurant = ['id' => '120', 'name' => 'Restaurant'];
$attributeOne = ['id' => '120', 'value' => 'one', 'restaurant_id' => '120'];
$attributeTwo = ['id' => '121', 'value' => 'two', 'restaurant_id' => '120'];

$I->haveInDatabase('restaurants', $restaurant);
$I->haveInDatabase('restaurantAttributeValues', $attributeOne);
$I->haveInDatabase('restaurantAttributeValues', $attributeTwo);

$I->sendGET('restaurants');

$I->seeResponseContainsJson($restaurant);