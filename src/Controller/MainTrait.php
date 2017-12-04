<?php
namespace Gap\Base\Controller;

use Gap\Base\App;
use Gap\Http\Request;
use Gap\Http\Response;
use Gap\Routing\Route;

trait MainTrait
{
    protected $app;
    protected $config;
    protected $request;
    protected $route;

    protected $params = [];

    public function __construct(App $app, Request $request, Route $route)
    {
        $this->app = $app;
        $this->config = $app->getConfig();
        $this->request = $request;
        $this->route = $route;
        /*
        if ($route = $request->getRoute()) {
            $this->params = $route->getParams();
        }
        */
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

    protected function response(string $content): Response
    {
        return new Response($content);
    }
}
