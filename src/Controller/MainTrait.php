<?php
namespace Gap\Base\Controller;

use Gap\Base\App;
use Gap\Http\Request;
use Gap\Http\SiteManager;
use Gap\Http\SiteUrlBuilder;

use Gap\Routing\Router;
use Gap\Routing\Route;
use Gap\Routing\RouteUrlBuilder;

trait MainTrait
{
    protected $app;
    protected $config;
    protected $siteManager;
    protected $router;

    protected $request;
    protected $route;

    protected $params = [];

    protected $siteUrlBuilder;
    protected $routeUrlBuilder;

    public function __construct(
        App $app,
        SiteManager $siteManager,
        Router $router,
        Request $request,
        Route $route
    ) {
        $this->app = $app;
        $this->config = $app->getConfig();
        $this->siteManager = $siteManager;
        $this->router = $router;

        $this->request = $request;
        $this->route = $route;
    }

    public function getApp(): App
    {
        return $this->app;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getParam(string $key): string
    {
        return $this->route->getParam($key);
    }

    public function bootstrap(): void
    {
    }

    protected function getSiteUrlBuilder(): SiteUrlBuilder
    {
        if ($this->siteUrlBuilder) {
            return $this->siteUrlBuilder;
        }

        $this->siteUrlBuilder = new SiteUrlBuilder($this->getSiteManager());
        return $this->siteUrlBuilder;
    }

    protected function getRouteUrlBuilder(): RouteUrlBuilder
    {
        if ($this->routeUrlBuilder) {
            return $this->routeUrlBuilder;
        }

        $this->routeUrlBuilder = new RouteUrlBuilder($this->router, $this->siteUrlBuilder);
        return $this->routeUrlBuilder;
    }

    protected function getSiteManager(): SiteManager
    {
        return $this->siteManager;
    }

    protected function getRouter(): Router
    {
        return $this->router;
    }
}
