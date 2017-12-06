<?php
namespace Gap\Base\Controller\View;

use Gap\Routing\SiteUrlBuilder;
use Gap\Routing\RouteUrlBuilder;

class RegisterUrl extends RegisterBase
{
    public function register(): void
    {
        if ($siteUrlBuilder = $this->app->get('siteUrlBuilder')) {
            $this->registerSiteUrl($siteUrlBuilder);
        }

        if ($routeUrlBuilder = $this->app->get('routeUrlBuilder')) {
            $this->registerRouteUrl($routeUrlBuilder);
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
        if ($this->app->has('localeManager')) {
            $localeManager = $this->app->get('localeManager');
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
}
