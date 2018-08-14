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
        $this->routeCollection->add('load' . $presentationService,
            (new RouteBuilder())
                ->setPath($path)
                ->setService($presentationService)
                ->setServiceMethod('load')
                ->build()
        );

        $this->routeCollection->add('create' . $presentationService,
            (new RouteBuilder())
                ->setPath($path)
                ->setService($presentationService)
                ->setServiceMethod('create')
                ->setPOST()
                ->build()
        );

        $this->routeCollection->add('update' . $presentationService,
            (new RouteBuilder())
                ->setPath($path . '/{id}')
                ->setService($presentationService)
                ->setServiceMethod('update')
                ->setPUT()
                ->build()
        );

        $this->routeCollection->add('delete' . $presentationService,
            (new RouteBuilder())
                ->setPath($path. '/{id}')
                ->setService($presentationService)
                ->setServiceMethod('delete')
                ->setDELETE()
                ->build()
        );

        $this->routeCollection->add('get' . $presentationService,
            (new RouteBuilder())
                ->setPath($path. '/{id}')
                ->setService($presentationService)
                ->setServiceMethod('get')
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