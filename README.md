# ddd-skeleton
The project was created to help developers in DDD principle implementation.  

#Routing

Use syntax sugar for routing rules

````
$routesCollectionBuilder = new RouteCollectionBuilder();

$routesCollectionBuilder->addCRUD('/restaurants', Restaurant::class);

$routeCollection = $routesCollectionBuilder->build();

$routeCollection->add('whateverYouWant',
    (new RouteBuilder())
        ->setPath('you/url')
        ->setService('YouService')
        ->setServiceMethod('youMethod')
        ->build()
);

return $routeCollection;

````