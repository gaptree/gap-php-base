<?php
namespace Gap\Base\Controller\View;

use Gap\Base\App;
use Gap\Http\Request;
use Foil\Engine;

abstract class RegisterBase
{
    protected $app;
    protected $request;
    protected $engine;

    public function __construct(App $app, Request $request, Engine $engine)
    {
        $this->app = $app;
        $this->request = $request;
        $this->engine = $engine;
    }

    abstract public function register();
}
