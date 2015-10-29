<?php
namespace MrfExpressive\Hateoas;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use MrfExpressive\Hateoas\Response as HateoasResponse;
use Zend\Expressive\Router\RouterInterface;
use Zend\Stratigility\MiddlewareInterface;

class Middleware implements MiddlewareInterface
{
    /**
     * @var RouterInterface
     */
    protected $router;
    /**
     * @var Config
     */
    protected $config;
    /**
     * @var callable
     */
    protected $extractor;


    public function __construct(RouterInterface $router, Config $config, callable $extractor = null)
    {
        $this->router    = $router;
        $this->config    = $config;
    }


    /**
     * @param Request $request
     * @param Response $response
     * @param callable|null $out
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        if (!$response instanceof HateoasResponse) {
            return $out($request, $response);
        }

        $data = $response->getData();

        switch (true) {
            case $data instanceof \Traversable:
                return $out($request, $this->handleCollection($request, $response));
            case is_array($data):
                $responseFactory = $response->getResponseFactory();
                return $out($request, $responseFactory($response->getData()));
        }
    }

    protected function handleCollection(Request $request, HateoasResponse $response)
    {
        $responseFactory   = $response->getResponseFactory();
        $totalDataElements = 0;
        $data              = $response->getData();
        $pageQueryKey      = $this->config->getPageQueryKey();
        $sizeQueryKey      = $this->config->getSizeQueryKey();
        $defaultSize       = $this->config->getDefaultSize();

        if (is_array($data) || $data instanceof \Countable) {
            $totalDataElements = count($data);
        }

        $aggregatedData = [];
        if ($data instanceof \Traversable) {
            foreach ($data as $item) {
                $aggregatedData[] = $item;
            }
        }

        $queryParams = $request->getQueryParams();
        $page = isset($queryParams[$pageQueryKey]) ? $queryParams[$pageQueryKey] : 1;
        $size = isset($queryParams[$sizeQueryKey]) ? $queryParams[$sizeQueryKey] : $defaultSize;
        $totalPages = ceil($totalDataElements / $size);


        $baseUrl = $this->router->generateUri($response->getRouteName(), $request->getAttributes());

        $struct = [
            '_links' => [
                'first'   => ['href' => $this->generateUrl($baseUrl, 1, $size)],
                'current' => ['href' => $this->generateUrl($baseUrl, $page, $size)],
                'last'    => ['href' => $this->generateUrl($baseUrl, $totalPages, $size)],
            ]
        ];

        if ($page > 1) {
            $struct['_links']['prev'] = ['href' => $this->generateUrl($baseUrl, $page - 1, $size)];
        }

        if ($page < $totalPages) {
            $struct['_links']['next'] = ['href' => $this->generateUrl($baseUrl, $page + 1, $size)];
        }

        $response = $responseFactory(array_merge($aggregatedData, $struct));
        return $response;
    }

    protected function handleResource(Request $request, HateoasResponse $response)
    {

    }

    /**
     * @param string $baseUrl
     * @param string $page
     * @param int $size
     * @return string
     */
    protected function generateUrl($baseUrl, $page, $size)
    {
        $pageQueryKey   = $this->config->getPageQueryKey();
        $sizeQueryKey   = $this->config->getSizeQueryKey();

        $bindSymbol = (strstr($baseUrl, '?') ? '&' : '?');

        return $baseUrl . $bindSymbol . http_build_query([
            $pageQueryKey => $page,
            $sizeQueryKey => $size
        ]);
    }
}