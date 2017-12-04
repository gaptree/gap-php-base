<?php
namespace Gap\Base\ExceptionHandler;

abstract class ExceptionHandlerBase extends \Gap\Base\Ui\UiBase
{
    abstract public function handle(\RuntimeException $exception);
}
