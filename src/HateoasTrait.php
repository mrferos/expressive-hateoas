<?php
namespace MrfExpressive\Hateoas;

use Psr\Http\Message\ServerRequestInterface;

trait HateoasTrait
{
    /**
     * @var Config
     */
    public static $config;

    /**
     * @param ServerRequestInterface $request
     * @return int
     */
    public function getPage(ServerRequestInterface $request)
    {
        $config = self::$config ?: new Config([]);
        $queryParams = $request->getQueryParams();
        if (isset($queryParams[$config->getPageQueryKey()])) {
            return $queryParams[$config->getPageQueryKey()];
        }

        return 1;
    }

    /**
     * @param ServerRequestInterface $request
     * @return int
     */
    public function getSize(ServerRequestInterface $request)
    {
        $config = self::$config ?: new Config([]);
        $queryParams = $request->getQueryParams();
        if (isset($queryParams[$config->getSizeQueryKey()])) {
            return $queryParams[$config->getSizeQueryKey()];
        }

        return $config->getDefaultSize();
    }
}