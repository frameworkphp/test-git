<?php
return [
    'baseUri' => 'http://new.first.com/',
    'appName' => 'Welcome to PHP Framework',
    'database' => [
        'adapter' => 'Mysql',
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname' => 'framework'
    ],
    'application' => [
        'modelsDir' => APP_URL . 'models/',
        'libraryDir' => APP_URL . 'library/',
        'pluginsDir' => APP_URL . 'plugin/',
        'cacheDir' => APP_URL . 'cache/'
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
            'imageMaxSize' => 1242880,
            'imageQuality' => 95
        ]
    ]
];
