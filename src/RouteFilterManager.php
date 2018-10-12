<?php
namespace Gap\Base;

use Gap\Http\Request;
use Gap\Base\HttpHandler;
use Gap\Base\RouteFilter\RouteFilterBase;

class RouteFilterManager
{
    protected $filters = [];
    protected $httpHandler;

    public function __construct(HttpHandler $handler)
    {
        $this->httpHandler = $handler;
    }

    public function filter(Request $request, $route)
    {
        foreach ($route->getFilters() as $filterName) {
            $filter = $this->filters[$filterName] ?? null;
            if (!$filter) {
                throw new \Exception("Cannot find filter [$filterName]");
            }

            $filter->setHttpHandler($this->httpHandler);
            $filter->setRequest($request);
            $filter->setRoute($route);
            $filter->filter();
        }
    }

    public function addFilter(string $name, RouteFilterBase $filter): self
    {
        if (isset($this->filters[$name])) {
            throw new \Exception("Filter [$name] already exists");
        }

        $this->filters[$name] = $filter;
        return $this;
    }
}
