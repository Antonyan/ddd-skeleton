<?php

use Infrastructure\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\ExceptionListener;
use Symfony\Component\HttpKernel\EventListener\ResponseListener;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

$containerBuilder = new ContainerBuilder();
$containerBuilder->register('context', RequestContext::class);
$containerBuilder->register('matcher', UrlMatcher::class)
    ->setArguments([$routes, new Reference('context')])
;
$containerBuilder->register('requestStack', RequestStack::class);
$containerBuilder->register('controllerResolver', ControllerResolver::class);
$containerBuilder->register('argumentResolver', ArgumentResolver::class);

$containerBuilder->register('listener.router', RouterListener::class)
    ->setArguments([new Reference('matcher'), new Reference('requestStack')])
;
$containerBuilder->register('listener.response', ResponseListener::class)
    ->setArguments(['UTF-8'])
;
$containerBuilder->register('listener.exception', ExceptionListener::class)
    ->setArguments(['App\Services\Error::exceptionAction'])
;

$containerBuilder->register('listener.custom.response', \Infrastructure\Listeners\ResponseListener::class);
$containerBuilder->register('listener.request', \Infrastructure\Listeners\RequestListener::class);

$containerBuilder->register('dispatcher', EventDispatcher::class)
    ->addMethodCall('addSubscriber', [new Reference('listener.router')])
    ->addMethodCall('addSubscriber', [new Reference('listener.response')])
    ->addMethodCall('addSubscriber', [new Reference('listener.exception')])
    ->addMethodCall('addListener', ['response', [new Reference('listener.custom.response'), 'onResponse']])
    ->addMethodCall('addListener', ['request', [new Reference('listener.request'), 'onRequest']])
;
$containerBuilder->register('application', Application::class)
    ->setArguments([
        new Reference('controllerResolver'),
        new Reference('requestStack'),
        new Reference('argumentResolver'),
        new Reference('dispatcher'),
    ])
;

return $containerBuilder;