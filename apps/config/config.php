<?php
return [
    'baseUri' => 'http://framework.com/',
    'database' => [
        'adapter' => 'Mysql',
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname' => 'framework',
        'prefix' => 'pn_'
    ],
    'application' => [
        'modelsDir' => ROOT_URL . '/apps/models/',
        'libraryDir' => ROOT_URL . '/apps/library/',
        'pluginsDir' => ROOT_URL . '/apps/plugin/',
        'cacheDir' => ROOT_URL . '/apps/cache/'
    ],
    'memcache' => [
        'host' => 'localhost',
        'port' => 11211,
    ]
];
