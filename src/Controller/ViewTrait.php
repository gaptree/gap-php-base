<?php
namespace Gap\Base\Controller;

use Gap\Base\Controller\View\RegisterMeta;
use Gap\Base\Controller\View\RegisterTrans;
use Gap\Base\Controller\View\RegisterUrl;
use Gap\Base\Controller\View\RegisterCsrf;
use Gap\Base\Controller\View\RegisterLocale;
use Gap\Http\Response;

trait ViewTrait
{
    private $viewEngine;

    protected function getViewEngine()
    {
        if ($this->viewEngine) {
            return $this->viewEngine;
        }

        $requestApp = $this->route->getApp();
        $this->viewEngine = $this->app->get('viewEngine');
        $this->viewEngine->addFolder(
            $this->config->get('baseDir')
            . '/' .$this->config->get("app.{$requestApp}.dir")
            . '/view'
        );

        $this->viewEngine->useData([
            'app' => $this->app,
            'config' => $this->app->getConfig(),
            'request' => $this->request,
            'route' => $this->route
        ]);

        $this->obj(new RegisterMeta($this->app, $this->request, $this->viewEngine))
            ->register();
        $this->obj(new RegisterTrans($this->app, $this->request, $this->viewEngine))
            ->register();
        $this->obj(new RegisterUrl($this->app, $this->request, $this->viewEngine))
            ->register();
        $this->obj(new RegisterCsrf($this->app, $this->request, $this->viewEngine))
            ->register();
        $this->obj(new RegisterLocale($this->app, $this->request, $this->viewEngine))
            ->register();

        return $this->viewEngine;
    }

    protected function render($tpl, $data)
    {
        $viewEngine = $this->getViewEngine();

        return $viewEngine->render($tpl, $data);
    }

    protected function view($tpl, $data = [])
    {
        return new Response($this->render($tpl, $data));
    }

    protected function obj($obj)
    {
        return $obj;
    }
}
