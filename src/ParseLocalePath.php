<?php
namespace Gap\Base;

use Gap\I18n\Locale\LocaleManager;
use Gap\Http\Request;
use Gap\Base\Exception\NoLocaleException;

class ParseLocalePath
{
    protected $localeManager;

    public function __construct(?LocaleManager $localeManager = null)
    {
        $this->localeManager = $localeManager;
    }

    public function parse(Request $request): string
    {
        $pathinfo = $request->getPathInfo();
        if (empty($this->localeManager)) {
            return $pathinfo;
        }

        $localeManager = $this->localeManager;
        if ($localeManager->getMode() !== 'path') {
            $localeManager->setLocaleKey($localeManager->getDefaultLocaleKey());
            return $pathinfo;
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
            $localeManager->setLocaleKey($path);
            return '/';
        }

        $localeKey = substr($path, 0, $pos);
        if (!$localeManager->isAvailableLocaleKey($localeKey)) {
            throw new \Exception('error request');
        }

        $path = substr($path, $pos);
        $localeManager->setLocaleKey($localeKey);
        return $path;
    }
}
