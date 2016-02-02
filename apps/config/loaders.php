<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerNamespaces([
    'Library' => APP_URL . 'library/',
    'Annotations' => APP_URL . 'library/Annotations/',
    'Phalcon' =>  APP_URL . 'library/Phalcon/'
])->register();

return $loader;
