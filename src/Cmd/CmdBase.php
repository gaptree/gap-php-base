<?php
namespace Gap\Base\Cmd;

use Gap\Base\App;

abstract class CmdBase
{
    protected $app;
    protected $argv;
    protected $argc;
    protected $baseDir;
    protected $isDev;

    protected $parameters = [];
    protected $options = [];
    protected $aliases = [];
    protected $required = [];

    protected $colors = [
        'black' => 30,
        'blue' => 34,
        'green' => 32,
        'cyan' => 36,
        'red' => 31,
        'purple' => 35,
        'yellow' => 33,
        'white' => 37
    ];

    public function __construct(App $app, array $argv = [], int $argc = 1)
    {
        $this->app = $app;
        $this->argv = $argv;
        $this->argc = $argc;

        $this->buildOpts();

        $this->baseDir = $this->app->getConfig()->str('baseDir');
        $this->isDev = $this->options['dev'] ?? false;
    }

    protected function buildOpts()
    {
        $args = array_slice($this->argv, 2);
        $argLen = $this->argc - 2;

        for ($i = 0; $i < $argLen; $i++) {
            $arg = $args[$i];

            // parameter: a abc
            if ($arg[0] !== '-') {
                $this->parameters[] = $arg;
                continue;
            }

            $option = $this->parseOption($arg);

            if (strpos($arg, '=') !== false) {
                throw new \Exception(
                    "\n"
                    . $this->color('red', $this->errLongOptions())
                    . $this->colorEnd()
                );
            }

            if ($i >= ($argLen - 1)) {
                $this->options[$option] = true;
                continue;
            }

            $next = $args[$i + 1];
            if ($next[0] !== '-') {
                $this->options[$option] = $next;
                $i++;
                continue;
            }

            $this->options[$option] = true;
        }
    }

    protected function parseOption($arg)
    {
        if ($arg[1] !== '-') {
            if (isset($arg[2])) {
                $msg = "\n"
                    . $this->color('red', $this->errOptions())
                    . $this->colorEnd();
                throw new \Exception($msg);
            }

            $alias = $arg[1];
            $option = $this->aliases[$alias] ?? $alias;

            return $option;
        }

        // option --option value2
        return trim($arg, '-/');
    }

    protected function color($color, $message)
    {
        $colorCode = $this->colors[$color] ?? 0;
        return "\033[{$colorCode}m" . $message;
    }

    protected function colorEnd($msg = "\n")
    {
        return "\033[0m$msg";
    }

    protected function errOptions()
    {
        return "legal: -a -b val1 -c val2\n" .
            "illegal: -ab -c=val1\n";
    }

    protected function errLongOptions()
    {
        return "legal: --option value\n" .
            "illegal: --option=value\n";
    }
}
