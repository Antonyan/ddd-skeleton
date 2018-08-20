<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

require_once __DIR__ . "/../vendor/autoload.php";

$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration([
    __DIR__ . "/../src/contexts/RestaurantManagement/RestaurantModule/Models",
    __DIR__ . "/../src/contexts/RestaurantManagement/RestaurantAttributeValueModule/Models",
], $isDevMode);

(new \Dotenv\Dotenv(__DIR__.'/../'))->load();

$connectionParams = require(__DIR__ . "/../migrations-db.php");

$entityManager = EntityManager::create($connectionParams, $config);
