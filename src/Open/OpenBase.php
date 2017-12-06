<?php
namespace Gap\Base\Open;

use Gap\Http\JsonResponse;

abstract class OpenBase
{
    use \Gap\Base\Controller\MainTrait;

    // deprecated
    protected function jsonResponse($data = null, int $status = 200, array $headers = array())
    {
        return new JsonResponse($data, $status, $headers);
    }

    protected function getOAuth2Server()
    {
        return $this->app->get('oauth2Server');
    }

    protected function getOAuth2Storage()
    {
        return $this->app->get('oauth2Storage');
    }
}