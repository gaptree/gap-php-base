<?php
namespace Gap\Base;

use Gap\I18n\Locale\LocaleManager;
use Gap\Base\Exception\NoLocaleException;

class ParseLocalePath
{
    protected $localeManager = null;
    protected $defaultLocaleKey = 'zh-cn';

    public function __construct(?LocaleManager $localeManager = null)
    {
        if ($localeManager) {
            $this->localeManager = $localeManager;
            $this->defaultLocaleKey = $localeManager->getDefaultLocaleKey();
        }
    }

    public function parse(string $pathinfo): array
    {
        if (empty($this->localeManager)) {
            return [$this->defaultLocaleKey, $pathinfo];
        }

        $localeManager = $this->localeManager;
        if ($localeManager->getMode() !== 'path') {
            return [$this->defaultLocaleKey, $pathinfo];
        }

        if ($pathinfo === '/') {
            throw new NoLocaleException('no locale');
        }

        $path = substr($pathinfo, 1);
        $pos = strpos($path, '/');
        if ($pos === false) {
            if (!$localeManager->isAvailableLocaleKey($path)) {
                throw new \Exception('error request');
            }
            //$localeManager->setLocaleKey($path);
            return [$path, '/'];
        }

        $localeKey = substr($path, 0, $pos);
        if (!$localeManager->isAvailableLocaleKey($localeKey)) {
            throw new \Exception('error request');
        }

        $path = substr($path, $pos);
        //$localeManager->setLocaleKey($localeKey);
        return [$localeKey, $path];
    }
}
