<?php
namespace Gap\Base\Ui;

use Gap\Base\Controller\ControllerBase;
use Gap\Base\View\Register\RegisterMeta;
use Gap\Base\View\Register\RegisterTrans;
use Gap\Base\View\Register\RegisterUrl;
use Gap\Base\View\Register\RegisterCsrf;
use Gap\Base\View\Register\RegisterLocale;
use Gap\Http\Response;
use Gap\Meta\Meta;
use Foil\Engine;

abstract class UiBase extends ControllerBase
{
    protected $viewEngine;
    protected $meta;

    protected function getViewEngine(): Engine
    {
        if ($this->viewEngine) {
            return $this->viewEngine;
        }

        $requestApp = $this->route->getApp();

        $config = $this->getConfig();

        $baseDir = $config->str('baseDir');
        if (empty($baseDir)) {
            throw new \Exception('cannot find "baseDir" config');
        }

        $this->viewEngine = \Foil\Foil::boot([
            'folders' => [$baseDir . '/view'],
            'autoescape' => false,
            'ext' => 'phtml'
        ])->engine();

        $appSubDir = $config->config('app')->config($requestApp)->str('dir');
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

        return $this->viewEngine;
    }

    protected function render(string $tpl, array $data): string
    {
        $viewEngine = $this->getViewEngine();

        return $viewEngine->render($tpl, $data);
    }

    protected function view(string $tpl, array $data = []): Response
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
                $this->app->getDmg()->connect($this->config->config('meta')->arr('db')),
                $this->app->getCmg()->connect($this->config->config('meta')->arr('cache'))
            );
        }

        return $this->meta;
    }
}
