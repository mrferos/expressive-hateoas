<?php
namespace MrfExpressive\Hateoas\Tests;

use Interop\Container\ContainerInterface;
use MrfExpressive\Hateoas\Config;
use MrfExpressive\Hateoas\Middleware;
use MrfExpressive\Hateoas\MiddlewareFactory;
use Zend\Expressive\Router\RouterInterface;

class MiddlewareFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetMiddleware()
    {
        $routerMock    = $this->getMock(RouterInterface::class);
        $containerMock = $this->getMock(ContainerInterface::class);
        $configMock    = $this->getMockBuilder(Config::class)
                                ->disableOriginalConstructor()
                                ->getMock();

        $containerMock->expects($this->any())
                        ->method('get')
                        ->willReturnCallback(function($service) use ($routerMock, $configMock) {
                            switch ($service) {
                                case RouterInterface::class:
                                    return $routerMock;
                                case Config::class:
                                    return $configMock;
                            }
                        });

        $middlewareFactory = new MiddlewareFactory();
        $this->assertInstanceOf(
            Middleware::class,
            $middlewareFactory($containerMock)
        );
    }
}