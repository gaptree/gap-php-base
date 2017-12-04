<?php
namespace Gap\Base\Controller\View;

use Gap\Http\UrlManager;
use Gap\Routing\BuildSiteUrl;
use Gap\Routing\BuildRouteUrl;

class RegisterUrl extends RegisterBase
{
    public function register()
    {
        $localeManager = $this->app->get('localeManager');
        $locale = $localeManager->getMode() === 'path' ?
            $localeManager->getLocaleKey() : '';

        $buildSiteUrl = new BuildSiteUrl($this->app->get('siteManager'));

        $buildRouteUrl = new BuildRouteUrl(
            $this->app->get('router'),
            $buildSiteUrl,
            $locale
        );

        $this->engine->registerFunction(
            'url',
            function ($site, $uri, $protocol = '') use ($buildSiteUrl) {
                return $buildSiteUrl->url($site, $uri, $protocol);
            }
        );

        $this->engine->registerFunction(
            'staticUrl',
            function ($uri, $protocol = '') use ($buildSiteUrl) {
                return $buildSiteUrl->staticUrl($uri, $protocol);
            }
        );

        $this->engine->registerFunction(
            'routeUrl',
            function ($name, $params = [], $query = [], $protocol = '') use ($buildRouteUrl) {
                return $buildRouteUrl->routeUrl($name, $params, $query, $protocol);
            }
        );

        $this->engine->registerFunction(
            'routeGet',
            function ($name, $params = [], $query = [], $protocol = '') use ($buildRouteUrl) {
                return $buildRouteUrl->routeGet($name, $params, $query, $protocol);
            }
        );

        $this->engine->registerFunction(
            'routePost',
            function ($name, $params = [], $query = [], $protocol = '') use ($buildRouteUrl) {
                return $buildRouteUrl->routePost($name, $params, $query, $protocol);
            }
        );

        $this->engine->registerFunction(
            'routeGetRest',
            function ($name, $params = [], $query = [], $protocol = '') use ($buildRouteUrl) {
                return $buildRouteUrl->routeGetRest($name, $params, $query, $protocol);
            }
        );

        $this->engine->registerFunction(
            'routePostRest',
            function ($name, $params = [], $query = [], $protocol = '') use ($buildRouteUrl) {
                return $buildRouteUrl->routePostRest($name, $params, $query, $protocol);
            }
        );
    }
}
