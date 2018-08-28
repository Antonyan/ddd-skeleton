<?php

use Infrastructure\Services\MySQLClient;

$containerBuilder->register('db', \Infrastructure\Services\DbConnection::class)
    ->addArgument($containerBuilder->get('config')->database);

$containerBuilder->register('MySqlClient', MySQLClient::class)
    ->addArgument($containerBuilder->get('config')->database);

