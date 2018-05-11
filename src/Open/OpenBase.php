<?php
namespace Gap\Base\Open;

use Gap\Base\Controller\ControllerBase;
use Gap\Http\JsonResponse;

abstract class OpenBase extends ControllerBase
{
    public function json(\JsonSerializable $obj): JsonResponse
    {
        return new JsonResponse($obj);
    }

    public function errorJson(\JsonSerializable $obj): JsonResponse
    {
        $response = new JsonResponse($obj);
        $response->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
        return $response;
    }
}
