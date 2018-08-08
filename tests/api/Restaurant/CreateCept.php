<?php

$I = new ApiTester($scenario);

$restaurant = ['name' => 'CreatedRestaurant'];

$I->setHeader('Content-type', 'application/json');
$I->sendPOST('restaurants', $restaurant);

$I->seeResponseContainsJson($restaurant);
