<?php

namespace Infrastructure\Models\Routing;

use Infrastructure\Exceptions\InfrastructureException;
use Symfony\Component\Routing\RouteCollection;

class RouteCollectionBuilder
{
    /**
     * @var RouteCollection
     */
    private $routeCollection;

    /**
     * RouteCollectionBuilder constructor.
     */
    public function __construct()
    {
        $this->routeCollection = new RouteCollection();
    }

    /**
     * @param string $path
     * @param string $presentationService
     * @return RouteCollectionBuilder
     * @throws InfrastructureException
     */
    public function addCRUD(string $path, string $presentationService) : RouteCollectionBuilder
    {
        $this->addGET($path, $presentationService, 'load')
            ->addPOST($path, $presentationService, 'create')
            ->addPUT($path . '/{id}', $presentationService, 'update')
            ->addDELETE($path . '/{id}', $presentationService, 'delete')
            ->addGET($path . '/{id}', $presentationService, 'get');

        return $this;
    }

    /**
     * @param string $url
     * @param string $presentationService
     * @param string $serviceMethod
     * @return RouteCollectionBuilder
     * @throws InfrastructureException
     */
    public function addGET(string $url, string $presentationService, string $serviceMethod) : RouteCollectionBuilder
    {
        return $this->addUrl('GET', $url, $presentationService, $serviceMethod);
    }

    /**
     * @param string $url
     * @param string $presentationService
     * @param string $serviceMethod
     * @return RouteCollectionBuilder
     * @throws InfrastructureException
     */
    public function addPOST(string $url, string $presentationService, string $serviceMethod) : RouteCollectionBuilder
    {
        return $this->addUrl('POST', $url, $presentationService, $serviceMethod);
    }

    /**
     * @param string $url
     * @param string $presentationService
     * @param string $serviceMethod
     * @return RouteCollectionBuilder
     * @throws InfrastructureException
     */
    public function addPUT(string $url, string $presentationService, string $serviceMethod) : RouteCollectionBuilder
    {
        return $this->addUrl('PUT', $url, $presentationService, $serviceMethod);
    }

    /**
     * @param string $url
     * @param string $presentationService
     * @param string $serviceMethod
     * @return RouteCollectionBuilder
     * @throws InfrastructureException
     */
    public function addDELETE(string $url, string $presentationService, string $serviceMethod) : RouteCollectionBuilder
    {
        return $this->addUrl('DELETE', $url, $presentationService, $serviceMethod);
    }

    /**
     * @param string $httpMethod
     * @param string $url
     * @param string $presentationService
     * @param string $serviceMethod
     * @return RouteCollectionBuilder
     * @throws InfrastructureException
     */
    public function addUrl(string $httpMethod, string $url, string $presentationService, string $serviceMethod) : RouteCollectionBuilder
    {
        $this->routeCollection->add($url . $presentationService . $serviceMethod,
            (new RouteBuilder())
                ->setPath($url)
                ->setService($presentationService)
                ->setServiceMethod($serviceMethod)
                ->setHttpMethod($httpMethod)
                ->build()
        );

        return $this;
    }

    /**
     * @return RouteCollection
     */
    public function build() : RouteCollection
    {
        return $this->routeCollection;
    }
}