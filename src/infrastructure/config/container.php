<?php

$containerBuilder->register('db', \Infrastructure\Services\DbConnection::class)
    ->addArgument($containerBuilder->get('config')->database);

