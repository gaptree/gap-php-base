<?php
namespace Gap\Base;

use Gap\Base\App;

use Gap\Routing\Route;
use Gap\Routing\Router;
use Gap\Routing\RouteFilterManager;

use Gap\Http\Request;
use Gap\Http\SiteManager;
use Gap\Http\Session\SessionBuilder;
use Gap\Http\RequestFilterManager;

use Symfony\Component\HttpFoundation\Response;

class HttpHandler
{
    protected $app;
    protected $siteManager;
    protected $router;

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

    public function getRequestFilterManager(): RequestFilterManager
    {
        if ($this->requestFilterManager) {
            return $this->requestFilterManager;
        }

        $this->requestFilterManager = new RequestFilterManager();
        return $this->requestFilterManager;
    }

    public function getRouteFilterManager(): RouteFilterManager
    {
        if ($this->routeFilterManager) {
            return $this->routeFilterManager;
        }

        $this->routeFilterManager = new RouteFilterManager();
        return $this->routeFilterManager;
    }

    protected function callControllerAction(Request $request, Route $route): Response
    {
        list($controllerClass, $fun) = explode('@', $route->getAction());

        if (!class_exists($controllerClass)) {
            throw new \Exception("class not found: $controllerClass");
        }

        //$controller = new $controllerClass($this->app, $request, $route, $this->siteManager);
        $controller = new $controllerClass(
            $this->app,
            $this->siteManager,
            $this->router,
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
