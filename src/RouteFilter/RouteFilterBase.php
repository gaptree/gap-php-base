<?php
namespace Gap\Base\RouteFilter;

use Gap\Base\App;
use Gap\Http\Request;
use Gap\Routing\Route;

abstract class RouteFilterBase
{
    protected $app;
    protected $request;
    protected $route;

    public function setApp(App $app)
    {
        $this->app = $app;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function setRoute(Route $route)
    {
        $this->route = $route;
    }

    abstract public function filter();
}
