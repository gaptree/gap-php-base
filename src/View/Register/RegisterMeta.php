<?php
namespace Gap\Base\View\Register;

use Gap\Meta\Meta;
use Foil\Engine;

class RegisterMeta implements RegisterInterface
{
    protected $meta;

    public function __construct(?Meta $meta)
    {
        $this->meta = $meta;
    }

    public function register(Engine $engine): void
    {
        if (empty($this->meta)) {
            $engine->registerFunction(
                'meta',
                function ($str) {
                    return "#?$str?";
                    //throw new \Exception('Please register meta');
                }
            );

            return;
        }

        $meta = $this->meta;
        $engine->registerFunction(
            'meta',
            function ($str, $vars = [], $localeKey = '') use ($meta) {
                return $meta->get($str, $vars, $localeKey);
            }
        );
    }
}
