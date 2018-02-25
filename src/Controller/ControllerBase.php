<?php
namespace Gap\Base\Controller;

use Gap\Base\App;
use Gap\Base\HttpHandler;
use Gap\Config\Config;

use Gap\Http\Request;
use Gap\Http\SiteManager;
use Gap\Http\SiteUrlBuilder;

use Gap\Routing\Router;
use Gap\Routing\Route;
use Gap\Routing\RouteUrlBuilder;

abstract class ControllerBase
{
    protected $handler;
    protected $app;
    protected $config;

    protected $request;
    protected $route;

    protected $params = [];

    public function __construct(
        HttpHandler $handler,
        Request $request,
        Route $route
    ) {
        $this->app = $handler->getApp();
        $this->handler = $handler;
        $this->config = $this->app->getConfig();

        $this->request = $request;
        $this->route = $route;
    }

    public function bootstrap(): void
    {
    }

    protected function getApp(): App
    {
        return $this->app;
    }

    protected function getConfig(): Config
    {
        return $this->config;
    }

    protected function getParam(string $key): string
    {
        return $this->route->getParam($key);
    }

    protected function getSiteManager(): SiteManager
    {
        return $this->handler->getSiteManager();
    }

    protected function getSiteUrlBuilder(): SiteUrlBuilder
    {
        return $this->handler->getSiteUrlBuilder();
    }

    protected function getRouteUrlBuilder(): RouteUrlBuilder
    {
        return $this->handler->getRouteUrlBuilder();
    }

    protected function getRouter(): Router
    {
        return $this->handler->getRouter();
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    protected function getRoute(): Route
    {
        return $this->route;
    }
}
