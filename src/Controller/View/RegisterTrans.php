<?php
namespace Gap\Base\Controller\View;

use Gap\I18n\Translator\Translator;

class RegisterTrans extends RegisterBase
{
    public function register()
    {
        if (!$this->app->has('translator')) {
            $this->engine->registerFunction(
                'trans',
                function () {
                    throw new \Exception('Please register translator');
                }
            );

            return;
        }

        $trans = $this->app->get('translator');

        $this->engine->registerFunction(
            'trans',
            function ($str, $vars = [], $localeKey = '') use ($trans) {
                return $trans->get($str, $vars, $localeKey);
            }
        );
    }
}
