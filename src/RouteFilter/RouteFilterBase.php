<?php
namespace Gap\Base\RouteFilter;

use Gap\Base\App;
use Gap\Base\HttpHandler;
use Gap\Http\Request;
use Gap\Routing\Route;

abstract class RouteFilterBase
{
    protected $httpHandler;
    protected $request;
    protected $route;

    public function setHttpHandler(HttpHandler $httpHandler)
    {
        $this->httpHandler = $httpHandler;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function setRoute(Route $route)
    {
        $this->route = $route;
    }

    protected function getApp(): App
    {
        return $this->httpHandler->getApp();
    }

    abstract public function filter();
}
