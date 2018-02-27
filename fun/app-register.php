<?php
use Gap\Base\App;

function register_router(App $app)
{
    $config = $app->getConfig();
    $app->set('router', function () use ($config) {
        $srcOpts = [];
        foreach ($config->get('app') as $appName => $appOpts) {
            $srcOpts[$appName]['dir'] = $appOpts['dir']
                . '/setting/router';
        }

        $routerBuilder = new \Gap\Routing\RouterBuilder(
            $config->get('baseDir'),
            $srcOpts
        );
        if (false === $config->get('debug')) {
            $routerBuilder
                ->setCacheFile('cache/setting-router-http.php');
        }
        return $routerBuilder->build();
    });
}

function register_session(App $app)
{
    $config = $app->getConfig();
    $app->set('session', function () use ($config) {
        return obj(new \Gap\Http\Session\SessionBuilder($config->get('session')))
            ->build();
    });
}

function register_view_engine(App $app)
{
    $config = $app->getConfig();
    $app->set('viewEngine', function () use ($config) {
        return \Foil\Foil::boot([
            'folders' => [$config->get('baseDir') . '/view'],
            'autoescape' => false,
            'ext' => 'phtml'
        ])->engine();
    });
}

function register_site_manager(App $app)
{
    $config = $app->getConfig();
    $app->set(
        'siteManager',
        \Gap\Http\SiteManager::class,
        [$config->get('site')]
    );
}

function register_site_url_builder(App $app)
{
    $app->set('siteUrlBuilder', function () use ($app) {
        return new \Gap\Http\SiteUrlBuilder($app->get('siteManager'));
    });
}

function register_route_url_builder(App $app)
{
    $app->set('routeUrlBuilder', function () use ($app) {
        $routeUrlBuilder = new \Gap\Routing\RouteUrlBuilder(
            $app->get('router'),
            $app->get('siteUrlBuilder')
        );

        if ($app->has('localeManager')) {
            $localeManager = $app->get('localeManager');
            if ('path' === $localeManager->getMode()) {
                $localeKey = $localeManager->getLocaleKey();
                $localeKey = $localeKey ? $localeKey : $localeManager->getDefaultLocaleKey();
                $routeUrlBuilder->setLocaleKey($localeKey);
            }
        }

        return $routeUrlBuilder;
    });
}

function register_locale_manager(App $app)
{
    $app->set(
        'localeManager',
        \Gap\I18n\Locale\LocaleManager::class,
        [$app->getConfig()->get('i18n.locale')]
    );
}

function register_dmg(App $app)
{
    $config = $app->getConfig();
    $app->set(
        'dmg',
        \Gap\Database\DatabaseManager::class,
        [$config->get('db'), $config->get('server.id')]
    );
}

function register_cmg(App $app)
{
    $config = $app->getConfig();
    $app->set(
        'cmg',
        \Gap\Cache\CacheManager::class,
        [$config->get('cache'), $config->get('server.id')]
    );
}

function register_translator(App $app)
{
    $config = $app->getConfig();
    $app->set('translator', function () use ($app, $config) {
        return new \Gap\I18n\Translator\Translator(
            $app->get('dmg')->connect($config->get('i18n.db')),
            $app->get('cmg')->connect($config->get('i18n.cache'))
        );
    });
}

function register_request_filter_manager(App $app, array $filters = [])
{
    $app->set('requestFilterManager', function () use ($app, $filters) {
        $manager = new \Gap\Base\RequestFilter\RequestFilterManager($app);
        $manager->addFilters($filters);
        return $manager;
    });
}

function register_route_filter_manager(App $app, array $filters = [])
{
    $app->set('routeFilterManager', function () use ($app, $filters) {
        $manager = new \Gap\Base\RouteFilter\RouteFilterManager($app);
        $manager->addFilters($filters);
        return $manager;
    });
}
