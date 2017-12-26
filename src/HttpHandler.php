<?php
namespace Gap\Base;

use Gap\Base\Exception\NotLoginException;
use Gap\Base\Exception\NoPermissionException;
use Gap\Base\Exception\NoLocaleException;
use Gap\Base\App;
use Gap\Routing\Route;
use Gap\Http\Request;
use Gap\Http\RedirectResponse;

class HttpHandler
{
    protected $app;
    protected $router;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->router = $this->app->get('router');
    }

    public function handle(Request $request)
    {
        $request->setSession($this->app->get('session'));
        $route = null;

        try {
            if ($this->app->has('requestFilterManager')) {
                $this->app->get('requestFilterManager')
                    ->filter($request);
            }

            $route = $this->router->dispatch(
                $this->app->get('siteManager')->getSite($request->getHttpHost()),
                $request->getMethod(),
                $this->parseLocalePath($request)
            );

            if ($this->app->has('routeFilterManager')) {
                $this->app->get('routeFilterManager')
                    ->filter($request, $route);
            }
            return $this->callControllerAction($request, $route);
        } catch (NoLocaleException $e) {
            $homeUrl = $this->app->get('routeUrlBuilder')
                ->routeUrl('home', [], ['ref' => $e->getMessage()]);
            return new RedirectResponse($homeUrl);
        } catch (NotLoginException $e) {
            return $this->handleException(
                $request,
                $this->app->getConfig()->get('exception.handler.notLogin'),
                $e,
                $route
            );
        } catch (NoPermissionException $e) {
            return $this->handleException(
                $request,
                $this->app->getConfig()->get('exception.handler.noPermission'),
                $e,
                $route
            );
        }
    }

    protected function parseLocalePath(Request $request)
    {
        $pathinfo = $request->getPathInfo();
        if (!$this->app->has('localeManager')) {
            return $pathinfo;
        }

        $localeManager = $this->app->get('localeManager');
        if ($localeManager->getMode() !== 'path') {
            $localeManager->setLocaleKey($localeManager->getDefaultLocaleKey());
            return $pathinfo;
        }

        if ($pathinfo === '/') {
            throw new NoLocaleException('no locale');
        }

        $path = substr($pathinfo, 1);
        $pos = strpos($path, '/');
        if ($pos === false) {
            if (!$localeManager->isAvailableLocaleKey($path)) {
                throw new \Exception('error request');
            }
            $localeManager->setLocaleKey($path);
            return '/';
        }

        $localeKey = substr($path, 0, $pos);
        if (!$localeManager->isAvailableLocaleKey($localeKey)) {
            throw new \Exception('error request');
        }

        $path = substr($path, $pos);
        $localeManager->setLocaleKey($localeKey);
        return $path;
    }


    protected function callControllerAction(Request $request, Route $route)
    {
        list($controllerClass, $fun) = explode('@', $route->getAction());

        if (!class_exists($controllerClass)) {
            throw new \Exception("class not found: $controllerClass");
        }

        $controller = new $controllerClass($this->app, $request, $route);
        if (!method_exists($controller, $fun)) {
            throw new \Exception("method not found: $controllerClass::$fun");
        }

        $controller->bootstrap();
        return $controller->$fun();
    }

    protected function handleException(Request $request, $handlerClass, $exception, $route)
    {
        if (empty($route)) {
            //$site = $this->app->get('siteManager')->getSite($request->getHttpHost());
            $route = new Route([
                'name' => 'exception',
                'site' => 'fake',
                'app' => 'fake',
                'access' => 'public'
            ]);
        }
        $handler = new $handlerClass($this->app, $request, $route);
        return $handler->handle($exception);
    }
}
