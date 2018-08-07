<?php

namespace Infrastructure\Events;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\Event;

class RequestEvent extends Event
{
    /**
     * @var Request
     */
    private $request;

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
    public function __construct(Request $request, $controller, string $methodName)
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
     * @return mixed
     */
    public function getController()
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