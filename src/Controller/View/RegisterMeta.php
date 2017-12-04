<?php
namespace Gap\Base\Controller\View;

use Gap\Meta\Meta;

class RegisterMeta extends RegisterBase
{
    public function register()
    {
        if (!$this->app->has('meta')) {
            $this->engine->registerFunction(
                'meta',
                function () {
                    throw new \Exception('Please register meta');
                }
            );

            return;
        }

        $meta = $this->app->get('meta');

        $this->engine->registerFunction(
            'meta',
            function ($str, $vars = [], $localeKey = '') use ($meta) {
                return $meta->get($str, $vars, $localeKey);
            }
        );
    }
}
