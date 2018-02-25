<?php
namespace Gap\Base\RequestFilter;

use Gap\Http\Request;
use Gap\Base\HttpHandler;
use Gap\Base\App;

abstract class RequestFilterBase
{
    protected $httpHandler;
    protected $request;

    public function setHttpHandler(HttpHandler $httpHandler)
    {
        $this->httpHandler = $httpHandler;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    protected function getApp(): App
    {
        return $this->httpHandler->getApp();
    }

    abstract public function filter();
}
