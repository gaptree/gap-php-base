<?php
namespace Gap\Base;

use Gap\Config\Config;

class App
{
    protected $config;
    protected $singletons = [];
    protected $refs = []; // reflection class & functions

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getConfig()
    {
        return $this->config;
    }

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
}
