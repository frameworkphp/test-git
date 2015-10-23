<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerNamespaces(array(
    'Annotations' => ROOT_URL . '/apps/library/Annotations/',
    'Phalcon' =>  ROOT_URL . '/apps/library/Phalcon/'
))->register();

return $loader;
