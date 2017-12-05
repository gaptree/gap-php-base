<?php
namespace Gap\Base\Controller\View;

class RegisterLocale extends RegisterBase
{
    public function register()
    {
        $app = $this->app;

        $this->engine->registerFunction(
            'getLocaleKey',
            function () use ($app) {
                if ($app->has('localeManager')) {
                    return $app->get('localeManager')->getLocaleKey();
                }

                return "";
            }
        );
    }
}
