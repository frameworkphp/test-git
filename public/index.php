<?php

use Phalcon\Mvc\Application;

error_reporting(E_ALL&~E_NOTICE);

ini_set('display_errors', 1);

define('ROOT_URL', realpath('..'));

try {

    /**
     * Read the configuration
     */
    $config = include ROOT_URL . '/apps/config/config.php';

    /**
     * Autoload composer
     */
    // require ROOT_URL . '/vendor/autoload.php';

    /**
     * Include services
     */
    require ROOT_URL . '/apps/config/services.php';

    /**
     * Handle the request
     */
    $application = new Application();

    /**
     * Assign the DI
     */
    $application->setDI($di);

    /**
     * Include modules
     */
    require ROOT_URL . '/apps/config/modules.php';

    echo $application->handle()->getContent();

} catch (Phalcon\Exception $e) {
    echo $e->getMessage();
} catch (PDOException $e) {
    echo $e->getMessage();
}
