<?php
namespace Gap\Base\RequestFilter;

use Gap\Base\App;
use Gap\Http\Request;

abstract class RequestFilterBase
{
    protected $app;
    protected $request;

    public function setApp(App $app)
    {
        $this->app = $app;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    abstract public function filter();
}
