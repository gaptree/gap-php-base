<?php
namespace Gap\Base\RouteFilter;

use Gap\Http\Request;
use Gap\Base\App;

class RouteFilterManager
{
    protected $filters = [];
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function filter(Request $request, $route)
    {
        foreach ($this->filters as $filter) {
            $filter->setApp($this->app);
            $filter->setRequest($request);
            $filter->setRoute($route);
            if ($res = $filter->filter()) {
                return $res;
            }
        }
    }

    public function addFilters(array $filters = [])
    {
        $this->filters = array_merge($this->filters, $filters);
        return $this;
    }
}
