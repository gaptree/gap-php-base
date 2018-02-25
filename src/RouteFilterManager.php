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
        foreach ($this->filters as $filter) {
            $filter->setHttpHandler($this->httpHandler);
            $filter->setRequest($request);
            $filter->setRoute($route);
            $filter->filter();
        }
    }

    public function addFilter(RouteFilterBase $filter): self
    {
        $this->filters[] = $filter;
        return $this;
    }
}
