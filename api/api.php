<?php

require_once __DIR__.'/../vendor/autoload.php';

use Infrastructure\Application;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__.'/../vendor/autoload.php';

$routes = include __DIR__ . '/../src/app/config/restRoutes.php';

$request = Request::createFromGlobals();

$response = (new Application($routes))->handle($request)->send();

$response->send();