<?php
namespace MrfExpressive\Hateoas\Tests;

use MrfExpressive\Hateoas\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider configValues
     */
    public function testDefaultOnNoConfigValue($configValues, $method)
    {
        $config = new Config($configValues);
        $this->assertEquals(
            $method[1],
            call_user_func([$config, $method[0]])
        );
    }

    public function configValues()
    {
        return [
            [
                [
                    'size_query_key' => 'size1',
                    'page_query_key' => 'page1'
                ],
                ['getDefaultSize', 25]
            ],
            [
                [
                    'size_query_key' => 'size1',
                    'default_size'   => '50'
                ],
                ['getPageQueryKey', 'page']
            ],
            [
                [
                    'page_query_key' => 'page1',
                    'default_size'   => '50'
                ],
                ['getSizeQueryKey', 'size']
            ],
        ];
    }
}