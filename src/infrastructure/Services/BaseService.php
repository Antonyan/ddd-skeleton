<?php

namespace Infrastructure\Services;

use Exception;
use Infrastructure\Exceptions\InfrastructureException;
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
     * @throws InfrastructureException
     * @throws Exception
     */
    protected function container() : ContainerBuilder
    {
        if ($this->container === null) {
            $dir = \dirname((new \ReflectionClass($this))->getFileName());

            $infrastructureDir = \dirname((new \ReflectionClass(self::class))->getFileName());

            $containerBuilder = new ContainerBuilder();

            $containerBuilder
                ->register('config', Config::class)->setArgument('$config', include $dir . '/../config/config.php');

            $this->container = $containerBuilder;

            $this->config()->merge(new Config(include $infrastructureDir . '/../config/config.php'));

            include $dir . '/../config/container.php';

            include $infrastructureDir . '/../config/container.php';
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