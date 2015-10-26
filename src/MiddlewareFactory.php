<?php
namespace MrfExpressive\Hateoas;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;

class MiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $middleware = new Middleware(
            $container->get(RouterInterface::class),
            $container->get(Config::class)
        );

        return $middleware;
    }
}