<?php

$I = new ApiTester($scenario);

$I->sendGET('restaurants');

$I->seeResponseContainsJson(['1' => 'Restaurant']);