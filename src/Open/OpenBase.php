<?php
namespace Gap\Base\Open;

use Gap\Base\Controller\ControllerBase;
use Gap\Http\JsonResponse;

abstract class OpenBase extends ControllerBase
{
    public function json($data): JsonResponse
    {
        return new JsonResponse($data);
    }

    public function errorJson($data): JsonResponse
    {
        return new JsonResponse($data, JsonResponse::HTTP_BAD_REQUEST);
    }
}
