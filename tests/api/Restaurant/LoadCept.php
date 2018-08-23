<?php

$I = new ApiTester($scenario);

$restaurant = ['id' => '120', 'name' => 'Restaurant'];
$attributeOne = ['id' => '120', 'value' => 'one', 'restaurantId' => '120'];
$attributeTwo = ['id' => '121', 'value' => 'two', 'restaurantId' => '120'];

$I->haveInDatabase('restaurants', $restaurant);
$I->haveInDatabase('restaurantAttributeValues', $attributeOne);
$I->haveInDatabase('restaurantAttributeValues', $attributeTwo);

$I->sendGET('restaurants');

$I->seeResponseContainsJson($restaurant);