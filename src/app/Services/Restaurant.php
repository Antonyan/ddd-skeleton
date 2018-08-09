<?php

namespace App\Services;

use Contexts\RestaurantManagement\Services\RestaurantManagementService;
use Exception;
use Infrastructure\Annotations\Validation;
use Infrastructure\Exceptions\InfrastructureException;
use Infrastructure\Services\AssociationsSerializer;
use Infrastructure\Services\BaseService;
use InvalidArgumentException;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;

class Restaurant extends BaseService
{
    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function load(Request $request) : Response
    {
        /** @var AssociationsSerializer $serializer */
        $serializer = $this->container()->get('associationsSerializer');
        return new Response($serializer->serialize($this->getRestaurantManagement()->loadRestaurants(), 'json', 'getName'));
    }

    /**
     * @Validation(name="id", type="integer", required=true)
     * @Validation(name="name", type="string", required=true, minLength=3)
     * @param Request $request
     * @return Response
     * @throws InfrastructureException
     * @throws InvalidArgumentException
     * @throws ReflectionException
     * @throws Exception
     */
    public function create(Request $request) : Response
    {
        /** @var Serializer $serializer */
        $serializer = $this->container()->get('serializer');
        return new Response($serializer->serialize($this->getRestaurantManagement()->create($request->request->all()), 'json'));
    }

    /**
     * @return RestaurantManagementService
     * @throws Exception
     */
    private function getRestaurantManagement() : RestaurantManagementService
    {
        return $this->container()->get('restaurantManagement');
    }
}