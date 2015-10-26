Expressive HATEOAS
====
the HATEOAS spec makes it easy to have a discoverable API and I needed it, so here it is :D
(if you're unfamiliar with it https://en.wikipedia.org/wiki/HATEOAS)

## Installation
Using composer!
```bash
composer require mrferos/expressive-hateoas
```

## Usage
Usage isn't the simplest and I'm working on making it easier but essentially you just need to return an instance of
`MrfExpressive\Hateoas\Response` with the collection you want to have the discoverable links added to. Below is an example
of usage:
```php
<?php

namespace App\Action;

use App\TestResult;
use MrfExpressive\Hateoas\HateoasTrait;
use MrfExpressive\Hateoas\Response as HateoasResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template;

class HomePageAction
{
    private $router;

    private $template;

    use HateoasTrait;

    public function __construct(Router\RouterInterface $router, Template\TemplateRendererInterface $template = null)
    {
        $this->router   = $router;
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $range = range(0, 100000);

        $page = $this->getPage($request);
        $size = $this->getSize($request);

        $selectedData = array_slice($range, ($page * $size) - $size, $size);
        $data = new TestResult($selectedData, count($range));
        return $next(
            $request,
            new HateoasResponse($data, 'home')
        );
    }
}
```

By default `MrfExpressive\Hateoas\Response` will assume you want a JsonResponse passed up the pipe, if you want to 
change this you can specify a callable third argument like so:
```php
return $next(
    $request,
    new HateoasResponse($data, 'home')
    function ($data) {
        return new Response($data);
    }
);
```