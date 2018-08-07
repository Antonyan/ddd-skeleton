<?php

/** @var \Composer\Autoload\ClassLoader $loader  */
$loader = require __DIR__.'/../vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationRegistry;
use Infrastructure\Application;
use Symfony\Component\HttpFoundation\Request;

$routes = include __DIR__ . '/../src/app/config/restRoutes.php';

/** Workaround for Doctrine annotation autoloader */
AnnotationRegistry::registerLoader(function($class) use ($loader) {
    return $loader->loadClass($class);
});

$request = (new \Infrastructure\Models\RichRequest())->createFromGlobals();

$response = (new Application($routes))->handle($request)->send();

$response->send();