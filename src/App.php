<?php
namespace Gap\Base;

use Gap\Config\Config;
use Gap\Database\DatabaseManager;
use Gap\Cache\CacheManager;

use Gap\I18n\Locale\LocaleManager;
use Gap\I18n\Translator\Translator;

class App
{
    protected $config;
    protected $databaseManager;
    protected $cacheManager;

    protected $localeManager;
    protected $translator;

    //protected $singletons = [];
    //protected $refs = []; // reflection class & functions

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function getDatabaseManager(): DatabaseManager
    {
        if ($this->databaseManager) {
            return $this->databaseManager;
        }

        $this->databaseManager = new DatabaseManager(
            $this->config->get('db'),
            $this->config->get('server.id')
        );
        return $this->databaseManager;
    }

    public function getCacheManager(): CacheManager
    {
        if ($this->cacheManager) {
            return $this->cacheManager;
        }

        $this->cacheManager = new CacheManager(
            $this->config->get('cache')
        );
        return $this->cacheManager;
    }

    public function getLocaleManager(): ?LocaleManager
    {
        if ($this->localeManager) {
            return $this->localeManager;
        }

        $localeOpts = $this->config->get('i18n.locale');
        if (empty($localeOpts)) {
            return null;
        }

        $this->localeManager = new LocaleManager($localeOpts);
        return $this->localeManager;
    }

    public function getTranslator(): Translator
    {
        if ($this->translator) {
            return $this->translator;
        }

        $this->translator = new Translator(
            $this->getDatabaseManager()->connect($this->config->get('i18n.db')),
            $this->getCacheManager()->connect($this->config->get('i18n.cache'))
        );
        return $this->translator;
    }


    /*
    public function set($key, $ref, $args = [])
    {
        $this->refs[$key] = [$ref, $args];
        return $this;
    }

    public function has($key)
    {
        return isset($this->refs[$key]);
    }

    public function get($key)
    {
        if ($obj = $this->singletons[$key] ?? null) {
            return $obj;
        }

        $this->singletons[$key] = $this->make($key);
        return $this->singletons[$key];
    }

    public function make($key)
    {
        list($ref, $args) = $this->refs[$key] ?? [null, null];
        if (!$ref) {
            throw new \Exception("Cannot find $key in App");
        }

        if (is_string($ref)) {
            $class = new \ReflectionClass($ref);
            $obj = $class->newInstanceArgs($args);
            return $obj;
        }

        if (is_object($ref) && ($ref instanceof \Closure)) {
            $fun = new \ReflectionFunction($ref);
            $obj = $fun->invokeArgs($args);
            return $obj;
        }

        throw new \Exception("Error reflection format for $key in App");
    }
    */
}
