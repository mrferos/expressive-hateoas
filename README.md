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

/** snipping namespaces for breviy **/
use MrfExpressive\Hateoas\HateoasTrait;
use MrfExpressive\Hateoas\Response as HateoasResponse;

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

The above code will result in a JSON response like so:
```json
{
    "0": 0,
    "1": 1,
    "2": 2,
    "3": 3,
    "4": 4,
    "5": 5,
    "6": 6,
    "7": 7,
    "8": 8,
    "9": 9,
    "10": 10,
    "11": 11,
    "12": 12,
    "13": 13,
    "14": 14,
    "15": 15,
    "16": 16,
    "17": 17,
    "18": 18,
    "19": 19,
    "20": 20,
    "21": 21,
    "22": 22,
    "23": 23,
    "24": 24,
    "_links": {
        "first": {
            "href": "\/customers\/1\/payments?page=1&size=25"
        },
        "current": {
            "href": "\/customers\/1\/payments?page=1&size=25"
        },
        "last": {
            "href": "\/customers\/1\/payments?page=4001&size=25"
        },
        "next": {
            "href": "\/customers\/1\/payments?page=2&size=25"
        }
    }
}
```

## Todos:
- [ ] Add more tests
- [ ] Make the traits respect configuration
- [ ] Come up with an easier way of using it
