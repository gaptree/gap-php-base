<?php
namespace Gap\Base\Rest;

use Gap\Http\JsonResponse;

abstract class RestBase
{
    use \Gap\Base\Controller\MainTrait;

    // deprecated
    protected function jsonResponse($data = null, int $status = 200, array $headers = array())
    {
        return new JsonResponse($data, $status, $headers);
    }
}
