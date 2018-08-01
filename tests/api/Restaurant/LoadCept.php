<?php

$I = new ApiTester($scenario);

$I->sendGET('restaurants');

$I->seeResponseContainsJson(['id' => '1', 'name' => 'Restaurant']);