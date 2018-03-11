<?php
namespace Gap\Base\View\Register;

use Gap\I18n\Translator\Translator;
use Foil\Engine;

class RegisterTrans implements RegisterInterface
{
    protected $translator;

    public function __construct(?Translator $translator)
    {
        $this->translator = $translator;
    }

    public function register(Engine $engine): void
    {
        if (empty($this->translator)) {
            $engine->registerFunction(
                'trans',
                function ($str) {
                    return ":?$str?";
                    //throw new \Exception('Please register translator');
                }
            );

            return;
        }

        $trans = $this->translator;

        $engine->registerFunction(
            'trans',
            function (string $str, string ...$vars) use ($trans) {
                return $trans->get($str, ...$vars);
            }
        );

        $engine->registerFunction(
            'localeTrans',
            function (string $localeKey, string $str, string ...$vars) use ($trans) {
                return $trans->localeGet($localeKey, $str, ...$vars);
            }
        );
    }
}
