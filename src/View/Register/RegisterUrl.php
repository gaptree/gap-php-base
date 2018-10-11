<?php
namespace Gap\Base\View\Register;

use Gap\Routing\SiteUrlBuilder;
use Gap\Routing\RouteUrlBuilder;
use Gap\I18n\Locale\LocaleManager;
use Foil\Engine;

class RegisterUrl implements RegisterInterface
{
    protected $siteUrlBuilder;
    protected $routeUrlBuilder;
    protected $localeManager;
    protected $engine;

    public function __construct(
        ?SiteUrlBuilder $siteUrlBuilder,
        ?RouteUrlBuilder $routeUrlBuilder,
        ?LocaleManager $localeManager
    ) {
        $this->siteUrlBuilder = $siteUrlBuilder;
        $this->routeUrlBuilder = $routeUrlBuilder;
        $this->localeManager = $localeManager;
    }

    public function register(Engine $engine): void
    {
        $this->engine = $engine;

        if ($this->siteUrlBuilder) {
            $this->registerSiteUrl($this->siteUrlBuilder);
        }

        if ($this->routeUrlBuilder) {
            $this->registerRouteUrl($this->routeUrlBuilder);
        }
    }

    protected function registerSiteUrl(SiteUrlBuilder $siteUrlBuilder): void
    {
        $this->engine->registerFunction(
            'url',
            function (
                string $site,
                string $uri,
                array $query = [],
                string $protocol = ''
            ) use ($siteUrlBuilder) {
                return $siteUrlBuilder->url($site, $uri, $query, $protocol);
            }
        );

        $this->engine->registerFunction(
            'staticUrl',
            function (string $uri, array $query = [], string $protocol = '') use ($siteUrlBuilder) {
                return $siteUrlBuilder->staticUrl($uri, $query, $protocol);
            }
        );
    }

    protected function registerRouteUrl(RouteUrlBuilder $routeUrlBuilder): void
    {
        if ($localeManager = $this->localeManager) {
            $localeKey = $localeManager->getMode() === 'path' ?
                $localeManager->getLocaleKey() : '';

            if ($localeKey) {
                $routeUrlBuilder->setLocaleKey($localeKey);
            }
        }

        $this->engine->registerFunction(
            'routeUrl',
            function (
                string $name,
                array $params = [],
                array $query = [],
                array $opts = []
            ) use ($routeUrlBuilder) {
                return $routeUrlBuilder->routeUrl($name, $params, $query, $opts);
            }
        );

        $this->engine->registerFunction(
            'routeGet',
            function (
                string $name,
                array $params = [],
                array $query = [],
                array $opts = []
            ) use ($routeUrlBuilder) {
                return $routeUrlBuilder->routeGet($name, $params, $query, $opts);
            }
        );

        $this->engine->registerFunction(
            'routePost',
            function (
                string $name,
                array $params = [],
                array $query = [],
                array $opts = []
            ) use ($routeUrlBuilder) {
                return $routeUrlBuilder->routePost($name, $params, $query, $opts);
            }
        );

        $this->registerRouteRestUrl($routeUrlBuilder); // deprecated

        $this->registerRouteOpenUrl($routeUrlBuilder);
    }

    protected function registerRouteRestUrl(RouteUrlBuilder $routeUrlBuilder): void
    {
        $this->engine->registerFunction(
            'routeGetRest',
            function (
                string $name,
                array $params = [],
                array $query = [],
                array $opts = []
            ) use ($routeUrlBuilder) {
                return $routeUrlBuilder->routeGetRest($name, $params, $query, $opts);
            }
        );

        $this->engine->registerFunction(
            'routePostRest',
            function (
                string $name,
                array $params = [],
                array $query = [],
                array $opts = []
            ) use ($routeUrlBuilder) {
                return $routeUrlBuilder->routePostRest($name, $params, $query, $opts);
            }
        );
    }

    protected function registerRouteOpenUrl(RouteUrlBuilder $routeUrlBuilder): void
    {
        $this->engine->registerFunction(
            'routeGetOpen',
            function (
                string $name,
                array $params = [],
                array $query = [],
                array $opts = []
            ) use ($routeUrlBuilder) {
                return $routeUrlBuilder->routeGetOpen($name, $params, $query, $opts);
            }
        );

        $this->engine->registerFunction(
            'routePostOpen',
            function (
                string $name,
                array $params = [],
                array $query = [],
                array $opts = []
            ) use ($routeUrlBuilder) {
                return $routeUrlBuilder->routePostOpen($name, $params, $query, $opts);
            }
        );
    }
}
