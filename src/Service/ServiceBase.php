<?php
namespace Gap\Base\Service;

use Gap\Base\App;

abstract class ServiceBase
{
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    protected function getConfig()
    {
        return $this->app->getConfig();
    }

    protected function getDmg()
    {
        return $this->app->get('dmg');
    }

    protected function getCmg()
    {
        return $this->app->get('cmg');
    }
}
