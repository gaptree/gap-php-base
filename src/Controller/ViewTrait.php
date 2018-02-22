<?php
namespace Gap\Base\Controller;

use Gap\Base\View\Register\RegisterMeta;
use Gap\Base\View\Register\RegisterTrans;
use Gap\Base\View\Register\RegisterUrl;
use Gap\Base\View\Register\RegisterCsrf;
use Gap\Base\View\Register\RegisterLocale;
use Gap\Http\Response;
use Gap\Meta\Meta;

trait ViewTrait
{
    protected $viewEngine;
    protected $meta;

    protected function getViewEngine()
    {
        if ($this->viewEngine) {
            return $this->viewEngine;
        }

        $requestApp = $this->route->getApp();

        $baseDir = $this->config->get('baseDir');
        if (empty($baseDir)) {
            throw new \Exception('cannot find "baseDir" config');
        }

        $this->viewEngine = \Foil\Foil::boot([
            'folders' => [$this->config->get('baseDir') . '/view'],
            'autoescape' => false,
            'ext' => 'phtml'
        ])->engine();

        $appSubDir = $this->config->get("app.{$requestApp}.dir");
        if (empty($appSubDir)) {
            throw new \Exception("Cannot find \"app.{$requestApp}.dir\" config");
        }
        $this->viewEngine->addFolder(
            $baseDir . '/' . $appSubDir . '/view'
        );

        $this->viewEngine->useData([
            'app' => $this->app,
            'config' => $this->app->getConfig(),
            'request' => $this->request,
            'route' => $this->route
        ]);

        (new RegisterMeta($this->getMeta()))->register($this->viewEngine);
        (new RegisterTrans($this->app->getTranslator()))->register($this->viewEngine);
        (new RegisterUrl(
            $this->getSiteUrlBuilder(),
            $this->getRouteUrlBuilder(),
            $this->app->getLocaleManager()
        ))->register($this->viewEngine);
        (new RegisterCsrf($this->request))->register($this->viewEngine);
        (new RegisterLocale($this->app->getLocaleManager()))->register($this->viewEngine);

        /*
        (new RegisterMeta($this->app, $this->request, $this->viewEngine))
            ->register();
        (new RegisterTrans($this->app, $this->request, $this->viewEngine))
            ->register();
        (new RegisterUrl($this->app, $this->request, $this->viewEngine))
            ->register();
        (new RegisterCsrf($this->app, $this->request, $this->viewEngine))
            ->register();
        (new RegisterLocale($this->app, $this->request, $this->viewEngine))
            ->register();
        */

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

    protected function getMeta(): ?Meta
    {
        if ($this->meta) {
            return $this->meta;
        }

        if ($this->config->has('meta')) {
            $this->meta = new \Gap\Meta\Meta(
                $this->app->getDmg()->connect($this->config->get('meta.db')),
                $this->app->getCmg()->connect($this->config->get('meta.cache'))
            );
        }

        return $this->meta;
    }
}
