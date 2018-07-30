<?php

namespace Infrastructure\Services;

use Exception;
use Infrastructure\Models\Config;
use ReflectionException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\BadMethodCallException;

class BaseService
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @return ContainerBuilder
     * @throws ReflectionException
     * @throws BadMethodCallException
     */
    protected function container() : ContainerBuilder
    {
        if ($this->container === null) {
            $dir = \dirname((new \ReflectionClass($this))->getFileName());
            $infrastructureDir = \dirname((new \ReflectionClass(self::class))->getFileName());
            $this->container = include $dir . '/../config/container.php';
            $this->container->merge(include $infrastructureDir . '/../config/container.php');
            $this->container
                ->register('config', Config::class)->setArgument('$config', include $dir . '/../config/config.php');
        }

        return $this->container;
    }

    /**
     * @return Config
     * @throws ReflectionException
     * @throws Exception
     */
    protected function config() : Config
    {
        return $this->container()->get('config');
    }
}