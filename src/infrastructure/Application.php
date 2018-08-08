<?php

namespace Infrastructure;

use Exception;
use Infrastructure\Events\RequestEvent;
use Infrastructure\Events\ResponseEvent;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RouteCollection;

class Application extends HttpKernel
{
    /**
     * @var UrlMatcherInterface
     */
    private $matcher;

    /**
     * @var ControllerResolver
     */
    private $controllerResolver;

    /**
     * @var ArgumentResolver
     */
    private $argumentResolver;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * Application constructor.
     * @param RouteCollection $routes
     * @throws Exception
     */
    public function __construct(RouteCollection $routes) {
        /** @var ContainerBuilder $container */
        $container = include __DIR__.'/config/appContainer.php';
        $this->matcher = $container->get('matcher');
        $this->controllerResolver = $container->get('controllerResolver');
        $this->argumentResolver = $container->get('argumentResolver');
        $this->eventDispatcher = $container->get('dispatcher');
        parent::__construct(
            $this->eventDispatcher,
            $this->controllerResolver,
            $container->get('requestStack'),
            $this->argumentResolver
        );
    }

    /**
     * @param Request $request
     * @param int $type
     * @param bool $catch
     * @return Response
     * @throws Exception
     */
    public function handle(Request $request,
        $type = HttpKernelInterface::MASTER_REQUEST,
        $catch = true)
    {
        $this->matcher->getContext()->fromRequest($request);

        try {
            $request->attributes->add($this->matcher->match($request->getPathInfo()));

            $controller = $this->controllerResolver->getController($request);
            $arguments = $this->argumentResolver->getArguments($request, $controller);

            $this->eventDispatcher->dispatch('request', new RequestEvent($request, $controller[0], $controller[1]));

            $response = call_user_func_array($controller, $arguments);
        } catch (Exception $exception) {

            if ($catch === false) {
                throw $exception;
            }

            $response = $this->handleException($request, $type, $exception);
        }

        $this->eventDispatcher->dispatch('response', new ResponseEvent($response, $request));

        return $response;
    }

    /**
     * @param Request $request
     * @param int $type
     * @param Exception $exception
     * @return Response
     */
    private function handleException(Request $request,int $type, \Exception $exception): Response
    {
        $event = new GetResponseForExceptionEvent($this, $request, $type, $exception);
        $this->dispatcher->dispatch(KernelEvents::EXCEPTION, $event);

        // a listener might have replaced the exception
        $exception = $event->getException();

        if (! $event->hasResponse()) {
            throw $exception;
        }

        return $event->getResponse();
    }
}