<?php
namespace Gap\Base\RequestFilter;

use Gap\Http\Request;
use Gap\Base\App;

class RequestFilterManager
{
    protected $filters = [];
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function filter(Request $request): void
    {
        foreach ($this->filters as $filter) {
            $filter->setApp($this->app);
            $filter->setRequest($request);
            $filter->filter($request);
        }
    }

    public function addFilters(array $filters = []): self
    {
        $this->filters = array_merge($this->filters, $filters);
        return $this;
    }
}
