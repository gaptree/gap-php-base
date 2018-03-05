<?php
namespace Gap\Base;

use Gap\Base\App;

use Gap\Routing\Route;
use Gap\Routing\Router;
use Gap\Routing\RouteUrlBuilder;

use Gap\Http\Request;
use Gap\Http\SiteManager;
use Gap\Http\SiteUrlBuilder;

use Gap\Base\RouteFilterManager;
use Gap\Base\RequestFilterManager;

use Symfony\Component\HttpFoundation\Response;

class HttpHandler
{
    protected $app;
    protected $router;

    protected $siteManager;
    protected $siteUrlBuilder;
    protected $routeUrlBuilder;

    protected $requestFilterManager;
    protected $routeFilterManager;

    public function __construct(
        App $app,
        SiteManager $siteManager,
        Router $router
    ) {
        $this->app = $app;
        $this->siteManager = $siteManager;
        $this->router = $router;
    }

    public function handle(Request $request): Response
    {
        $this->getRequestFilterManager()->filter($request);
        $route = $this->router->dispatch(
            $this->siteManager->getSite($request->getHttpHost()),
            $request->getMethod(),
            (new ParseLocalePath($this->app->getLocaleManager()))->parse($request)
        );
        $this->getRouteFilterManager()->filter($request, $route);

        return $this->callControllerAction($request, $route);
    }

    public function getApp(): App
    {
        return $this->app;
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function getRequestFilterManager(): RequestFilterManager
    {
        if ($this->requestFilterManager) {
            return $this->requestFilterManager;
        }

        $this->requestFilterManager = new RequestFilterManager($this);
        return $this->requestFilterManager;
    }

    public function getRouteFilterManager(): RouteFilterManager
    {
        if ($this->routeFilterManager) {
            return $this->routeFilterManager;
        }

        $this->routeFilterManager = new RouteFilterManager($this);
        return $this->routeFilterManager;
    }

    public function getSiteManager(): SiteManager
    {
        return $this->siteManager;
    }

    public function getSiteUrlBuilder(): SiteUrlBuilder
    {
        if ($this->siteUrlBuilder) {
            return $this->siteUrlBuilder;
        }

        $this->siteUrlBuilder = new SiteUrlBuilder($this->getSiteManager());
        return $this->siteUrlBuilder;
    }

    public function getRouteUrlBuilder(): RouteUrlBuilder
    {
        if ($this->routeUrlBuilder) {
            return $this->routeUrlBuilder;
        }

        $this->routeUrlBuilder = new RouteUrlBuilder($this->router, $this->siteUrlBuilder);
        return $this->routeUrlBuilder;
    }

    //
    // protected
    //
    protected function callControllerAction(Request $request, Route $route): Response
    {
        list($controllerClass, $fun) = explode('@', $route->getAction());

        if (!class_exists($controllerClass)) {
            throw new \Exception("class not found: $controllerClass");
        }

        //$controller = new $controllerClass($this->app, $request, $route, $this->siteManager);
        $controller = new $controllerClass(
            $this,
            $request,
            $route
        );

        if (!method_exists($controller, $fun)) {
            throw new \Exception("method not found: $controllerClass::$fun");
        }

        $controller->bootstrap();
        return $controller->$fun();
    }
}
