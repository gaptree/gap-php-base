<?php
namespace Gap\Base\View\Register;

use Foil\Engine;
use Gap\I18n\Locale\LocaleManager;

class RegisterLocale implements RegisterInterface
{
    protected $localeManager;
    
    public function __construct(?LocaleManager $localeManager)
    {
        $this->localeManager = $localeManager;
    }

    public function register(Engine $engine): void
    {
        $localeManager = $this->localeManager;

        $engine->registerFunction(
            'getLocaleKey',
            function () use ($localeManager) {
                if ($localeManager) {
                    return $localeManager->getLocaleKey();
                }

                return "";
            }
        );
    }
}
