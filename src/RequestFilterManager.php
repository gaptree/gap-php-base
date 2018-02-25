<?php
namespace Gap\Base;

use Gap\Http\Request;
use Gap\Base\HttpHandler;
use Gap\Base\RequestFilter\RequestFilterBase;

class RequestFilterManager
{
    protected $filters = [];
    protected $httpHandler;

    public function __construct(HttpHandler $httpHandler)
    {
        $this->httpHandler = $httpHandler;
    }

    public function filter(Request $request): void
    {
        foreach ($this->filters as $filter) {
            $filter->setHttpHandler($this->httpHandler);
            $filter->setRequest($request);
            $filter->filter($request);
        }
    }

    public function addFilter(RequestFilterBase $filter): self
    {
        $this->filters[] = $filter;
        return $this;
    }
}
