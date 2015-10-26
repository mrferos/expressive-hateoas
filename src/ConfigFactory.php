<?php
namespace MrfExpressive\Hateoas;

use Interop\Container\ContainerInterface;

class ConfigFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $configData = [];
        $configService = $container->get('config');
        if (isset($configService['hateoas'])) {
            $configData = $configService['hateoas'];
        }

        $config = new Config($configData);
        HateoasTrait::$config = $config;
        return $config;
    }
}