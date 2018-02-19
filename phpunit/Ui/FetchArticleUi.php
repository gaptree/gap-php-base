<?php
namespace phpunit\Gap\Base\Ui;

use Gap\Http\JsonResponse;

class FetchArticleUi
{
    public function bootstrap(): void
    {
    }

    public function show(): JsonResponse
    {
        return new JsonResponse(['welcome' => 'ok']);
    }
}
