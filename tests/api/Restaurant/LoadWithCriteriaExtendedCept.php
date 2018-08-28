<?php

$I = new ApiTester($scenario);

//$initialId = random_int(1000, 10000);
//
//for ($i = $initialId; $i <= $initialId + 10; $i++) {
//    $I->haveInDatabase('restaurants', ['id' => $i, 'name' => $I->randomHash() . 'one']);
//}
//
//for ($j = $initialId + 11; $j <= $initialId + 21; $j++) {
//    $I->haveInDatabase('restaurants', ['id' => $j, 'name' => $I->randomHash()]);
//}
//
//$I->sendGET('restaurants', ['gt' => '(id|'. ($initialId + 5) .')', 'like' => '(name|one)']);
//
//$response = json_decode($I->grabResponse(), true);
//
//$I->assertCount(5, $response['items']);
