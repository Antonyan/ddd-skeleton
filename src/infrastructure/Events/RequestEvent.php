<?php

namespace Infrastructure\Events;

use Infrastructure\Services\BaseService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\Event;

class RequestEvent extends Event
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var BaseService
     */
    private $controller;

    /**
     * @var string
     */
    private $methodName;

    /**
     * RequestEvent constructor.
     * @param Request $request
     * @param $controller
     * @param string $methodName
     */
    public function __construct(Request $request, BaseService $controller, string $methodName)
    {
        $this->request = $request;
        $this->controller = $controller;
        $this->methodName = $methodName;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return BaseService
     */
    public function getController() : BaseService
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->methodName;
    }
}