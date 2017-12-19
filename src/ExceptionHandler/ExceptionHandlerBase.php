<?php
namespace Gap\Base\ExceptionHandler;

use Gap\Http\ResponseInterface;

abstract class ExceptionHandlerBase extends \Gap\Base\Ui\UiBase
{
    abstract public function handle(\Exception $exception): ResponseInterface;
}
