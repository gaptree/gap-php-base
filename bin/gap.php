#!/usr/bin/env php
<?php
$tryFiles = [
    __DIR__ . '/../../../autoload.php',
    __DIR__ . '/../../autoload.php',
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/vendor/autoload.php',
];

$autoloadFile = '';

foreach ($tryFiles as $file) {
    if (file_exists($file)) {
        $autoloadFile = $file;
        break;
    }
}

if (empty($autoloadFile)) {
    throw new \Exception('Cannot find autoload file');
}

require $autoloadFile;

if (file_exists('setting')) {
    $baseDir = getcwd();
    $configBuilder = new \Gap\Config\ConfigBuilder(
        $baseDir . '/setting',
        $baseDir .  '/cache/setting-console.php'
    );
    $config = $configBuilder->build();
} else {
    $config = new \Gap\Config\Config([
        'baseDir' => getcwd()
    ]);
}

$dmg = $config->has('db') ?
    new \Gap\Database\DatabaseManager($config->arr('db'), $config->config('server')->str('id'))
    :
    null;
$cmg = $config->has('cache') ?
    new \Gap\Cache\CacheManager($config->arr('cache'))
    :
    null;

$app = new \Gap\Base\App($config, $dmg, $cmg);

$consoleHandler = new \Gap\Base\ConsoleHandler($app);
$consoleHandler->handle($argv, $argc);
