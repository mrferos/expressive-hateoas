<?php
namespace MrfExpressive\Hateoas;

use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use Zend\Diactoros\Response as DiactorosResponse;

class Response extends DiactorosResponse
{
    /**
     * @var callable
     */
    protected $responseFactory;
    /**
     * @var mixed
     */
    protected $data;
    /**
     * @var int
     */
    private $routeName;

    /**
     * @param mixed $data resource
     * @param int $status Status code for the response, if any.
     * @param array $headers Headers for the response, if any.
     * @throws InvalidArgumentException on any invalid element.
     */
    public function __construct($data, $routeName, callable $responseFactory = null, $status = 200, array $headers = [])
    {
        if (is_null($responseFactory)) {
            $responseFactory = function($data) {
                return new DiactorosResponse\JsonResponse($data);
            };
        }

        $this->data            = $data;
        $this->responseFactory = $responseFactory;
        $this->routeName       = $routeName;
        parent::__construct($responseFactory($data)->getBody(), $status, $headers); // TODO: Change the autogenerated stub
    }

    /**
     * @return callable
     */
    public function getResponseFactory()
    {
        return $this->responseFactory;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getRouteName()
    {
        return $this->routeName;
    }
}