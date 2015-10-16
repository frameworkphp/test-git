<?php
return new \Phalcon\Config([
    'database' => [
        'adapter' => 'Mysql',
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname' => 'framework',
    ],
    'application' => [
        'modelsDir' => ROOT_URL . '/apps/models/',
        'libraryDir' => ROOT_URL . '/apps/library/',
        'pluginsDir' => ROOT_URL . '/apps/plugin/',
        'cacheDir' => ROOT_URL . '/apps/cache/',
        'baseUri' => '/framework/'
    ]
]);
