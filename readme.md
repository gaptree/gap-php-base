# gap-php-base

## Get started

init composer and create composer.json
```
$ composer init
```

```
$ composer require gap/base
$ composer require gap/util filp/whoops --save-dev
```

edit composer.json
```
{
    "name": "tec/article",
    "description": "TecPoster Article",
    "type": "project",
    "license": "MIT",
    "authors": [
        {
            "name": "zhanjh",
            "email": "zhanjh@126.com"
        }
    ],
    "require": {
        "gap/base": "^1.1",
        "gap/user": "^1.0",
        "gap/meta": "^1.0"
    },
    "require-dev": {
        "gap/util": "^1.1",
        "filp/whoops": "^2.1"
    },
    "scripts": {
        "test": [
            "@setting",
            "@lint",
            "@phpunit"
        ],
        "lint": [
            "@phpcs",
            "@phpmd",
            "@phpstan"
        ],
        "setting": "gap jsonifySetting",
        "phpunit": "phpunit",
        "phpstan": "phpstan analyse -l 7 -c phpstan.neon app",
        "phpcs": "phpcs --report=full --standard=psr2 --extensions=php app",
        "phpmd": "phpmd app text cleancode,codesize,controversial,design,naming,unusedcode",
        "gap": "gap"
    },
    "autoload": {
        "psr-4": {
            "Tec\\Article\\": "app/tec/article/src"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "phpunit\\Tec\\Article\\": "app/tec/article/phpunit"
        }
    }
}
```

create App

```
$ composer gap buildApp wec/order
```

create Module

```
$ composer gap buildModule wec/order/landing
```

create route
```
# app/wec/order/setting/router/landing.php
<?php
$collection = new \Gap\Routing\RouteCollection();
$collection
    ->site('www')
    ->access('public')

    ->get('/', 'home', 'Tec\Article\Landing\Ui\HomeUi@show')
return $collection;
```

create ui
```
$ composer gap buildEntity wec/order/landing/ui/homeUi
```
