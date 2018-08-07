<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

require_once __DIR__ . "/../vendor/autoload.php";

$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration([
    __DIR__ . "/../src/contexts/RestaurantManagement/RestaurantModule/Models",
    __DIR__ . "/../src/contexts/RestaurantManagement/RestaurantAttributeValueModule/Models",
], $isDevMode);

$connectionParams = array(
    'dbname' => 'ddd',
    'user' => 'root',
    'password' => 'root',
    'host' => '127.0.0.1',
    'driver' => 'pdo_mysql',
);

$entityManager = EntityManager::create($connectionParams, $config);
