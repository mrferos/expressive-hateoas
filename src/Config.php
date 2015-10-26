<?php
namespace MrfExpressive\Hateoas;

class Config
{
    /**
     * @var string
     */
    protected $sizeQueryKey = 'size';

    /**
     * @var string
     */
    protected $pageQueryKey = 'page';

    /**
     * @var int
     */
    protected $defaultSize = 25;

    public function __construct(array $config)
    {
        isset($config['size_query_key']) && $this->sizeQueryKey = $config['size_query_key'];
        isset($config['page_query_key']) && $this->pageQueryKey = $config['page_query_key'];
        isset($config['default_size']) && $this->defaultSize = $config['default_size'];
    }

    /**
     * @return string
     */
    public function getSizeQueryKey()
    {
        return $this->sizeQueryKey;
    }

    /**
     * @return string
     */
    public function getPageQueryKey()
    {
        return $this->pageQueryKey;
    }

    /**
     * @return int
     */
    public function getDefaultSize()
    {
        return $this->defaultSize;
    }

}
