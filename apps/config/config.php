<?php
return [
    'baseUri' => 'http://new.first.com/',
    'database' => [
        'adapter' => 'Mysql',
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname' => 'framework'
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
    ],
    'cache' => 'file',
    'uploadPath' => 'public/uploads',
    'media' => [
        'user' => [
            'imagePath' => 'public/uploads/user',
            'imageResourceHost' => '',
            'imageMaxWidth' => 640,
            'imageMaxHeight' => 640,
            'imageMediumWidth' => 200,
            'imageMediumHeight' => 200,
            'imageThumbWidth' => 50,
            'imageThumbHeight' => 50,
            'imageMinSize' => 1000,
            'imageMaxSize' => 5242880,
            'imageQuality' => 95
        ]
    ]
];
