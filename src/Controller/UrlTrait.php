<?php
namespace Gap\Base\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Gap\Routing\BuildSiteUrl;
use Gap\Routing\BuildRouteUrl;

trait UrlTrait
{
    protected $buildRouteUrl;

    protected function gotoUrl($url, $status = 302)
    {
        return new RedirectResponse($url, $status);
    }

    protected function routeGet($name, $params = [], $query = [], $protocol = '')
    {
        return $this->getBuildRouteUrl()->routeGet($name, $params, $query, $protocol);
    }

    protected function gotoRouteGet($name, $params = [], $query = [], $protocol = '')
    {
        return $this->gotoUrl(
            $this->routeGet($name, $params, $query, $protocol)
        );
    }

    protected function getBuildRouteUrl()
    {
        if ($this->buildRouteUrl) {
            return $this->buildRouteUrl;
        }

        $localeManager = $this->app->get('localeManager');
        $locale = $localeManager->getMode() === 'path' ?
            $localeManager->getLocaleKey() : '';

        $this->buildRouteUrl = new BuildRouteUrl(
            $this->app->get('router'),
            new BuildSiteUrl($this->app->get('siteManager')),
            $locale
        );
        return $this->buildRouteUrl;
    }
}
