<?php

use Phalcon\Mvc\Application;

error_reporting(E_ALL&~E_NOTICE);

(new Phalcon\Debug)->listen();

define('ROOT_URL', realpath('..'));
define('TABLE_PREFIX', 'tb_');
define('HOST_HASH', substr(md5($_SERVER['HTTP_HOST']), 0, 12));

//try {

    /**
     * Autoload composer
     */
    require ROOT_URL . '/vendor/autoload.php';

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
//
//} catch (Phalcon\Exception $e) {
//    echo $e->getMessage();
//} catch (PDOException $e) {
//    echo $e->getMessage();
//}
