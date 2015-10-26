<?php
namespace MrfExpressive\Hateoas\Tests;

use Interop\Container\ContainerInterface;
use MrfExpressive\Hateoas\Config;
use MrfExpressive\Hateoas\ConfigFactory;

class ConfigFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetConfig()
    {
        $configMock = $this->getMock(\ArrayObject::class);
        $configMock->expects($this->any())
                    ->method('offsetGet')
                    ->with('hateoas')
                    ->willReturn([]);

        $container = $this->getMock(ContainerInterface::class);
        $container->expects($this->once())
                    ->method('get')
                    ->with('config')
                    ->willReturn($configMock);

        $configFactory = new ConfigFactory();
        $this->assertInstanceOf(
            Config::class,
            $configFactory($container)
        );
    }
}