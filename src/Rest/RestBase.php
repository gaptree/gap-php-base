<?php
namespace Gap\Base\Rest;

use Symfony\Component\HttpFoundation\JsonResponse;

abstract class RestBase
{
    use \Gap\Base\Controller\MainTrait;

    protected function jsonResponse($data = null, int $status = 200, array $headers = array())
    {
        return new JsonResponse($data, $status, $headers);
    }
}
