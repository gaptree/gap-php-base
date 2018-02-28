<?php
namespace Gap\Base\Service;

use Gap\Base\App;
use Gap\Config\Config;

class ServiceBase
{
    protected $app;

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
}
