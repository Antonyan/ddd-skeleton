<?php

$I = new ApiTester($scenario);

$firstId = $I->randomId();
$secondId = $I->randomId();

$restaurant = ['id' => $firstId, 'name' => 'Restaurant'];
$secondRestaurant = ['id' => $secondId, 'name' => 'RestaurantSecond'];
$attributeOne = ['id' => $I->randomId(), 'value' => 'one', 'restaurantId' => $firstId];
$attributeTwo = ['id' => $I->randomId(), 'value' => 'two', 'restaurantId' => $firstId];

for ($i = 0; $i < 10; $i++) {
    $I->haveInDatabase('restaurants', ['id' => $I->randomId(), 'name' => $I->randomHash()]);
}

$I->haveInDatabase('restaurants', $restaurant);
$I->haveInDatabase('restaurants', $secondRestaurant);
$I->haveInDatabase('restaurantAttributeValues', $attributeOne);
$I->haveInDatabase('restaurantAttributeValues', $attributeTwo);

$I->sendGET('restaurants', ['limit' => 5, 'orderByAsc' => 'id', 'offset' => 2]);

$response = json_decode($I->grabResponse(), true);

$I->assertCount(5, $response['items']);
