<?php
namespace Gap\Base\Service;

use Gap\Base\App;
use Gap\Config\Config;

class ServiceBase
{
    protected $app;
    protected $cacheName = 'default';

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    protected function getConfig(): Config
    {
        return $this->app->getConfig();
    }

    protected function getDmg()
    {
        return $this->app->getDmg();
    }

    protected function getCmg()
    {
        return $this->app->getCmg();
    }

    protected function getCache()
    {
        return $this->getCmg()->connect($this->cacheName);
    }

    protected function getApp(): App
    {
        return $this->app;
    }
}
