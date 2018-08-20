<?php

namespace Infrastructure\Models\Routing;

use Infrastructure\Exceptions\InfrastructureException;
use Symfony\Component\Routing\Route;

class RouteBuilder
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $service;

    /**
     * @var string
     */
    private $serviceMethod;

    /**
     * @var string
     */
    private $httpMethod = 'GET';

    /**
     * @param string $path
     * @return RouteBuilder
     */
    public function setPath(string $path): RouteBuilder
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @param string $service
     * @return RouteBuilder
     */
    public function setService(string $service): RouteBuilder
    {
        $this->service = $service;
        return $this;
    }

    /**
     * @param string $serviceMethod
     * @return RouteBuilder
     */
    public function setServiceMethod(string $serviceMethod): RouteBuilder
    {
        $this->serviceMethod = $serviceMethod;
        return $this;
    }

    /**
     * @param string $httpMethod
     * @return RouteBuilder
     */
    public function setHttpMethod(string $httpMethod): RouteBuilder
    {
        $this->httpMethod = $httpMethod;
        return $this;
    }

    /**
     * @return RouteBuilder
     */
    public function setPOST(): RouteBuilder
    {
        $this->httpMethod = 'POST';
        return $this;
    }

    /**
     * @return RouteBuilder
     */
    public function setPUT(): RouteBuilder
    {
        $this->httpMethod = 'PUT';
        return $this;
    }

    /**
     * @return RouteBuilder
     */
    public function setDELETE(): RouteBuilder
    {
        $this->httpMethod = 'DELETE';
        return $this;
    }

    /**
     * @return Route
     * @throws InfrastructureException
     */
    public function build() : Route
    {
        if (!$this->path || !$this->service || !$this->serviceMethod) {
            throw new InfrastructureException('You should always specify path, service and serviceMethod for router');
        }

        return new Route($this->path, [
            '_controller' => $this->service . '::' . $this->serviceMethod], [], [], '', [], [$this->httpMethod]);
    }
}