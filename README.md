# ddd-skeleton

The project was created to help developers in DDD principle implementation. 

#Request

````
This part will contain description about validation and filtering capabilities
````  

#Routing

Use syntax sugar for routing rules

````
$routesCollectionBuilder = new RouteCollectionBuilder();

$routesCollectionBuilder->addCRUD('/restaurants', Restaurant::class);

$routesCollectionBuilder->addGET('you/url', 'YouService', 'youMethod');

return $routesCollectionBuilder->build();

````
