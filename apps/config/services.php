<?php
/**
 * Services are globally registered in this file
 */

use Phalcon\Config;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Dispatcher;
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Mvc\Model\Metadata\Files as MetaDataAdapter;
use Phalcon\Annotations\Adapter\Files as AnnotationsAdapter;
use Annotations\AnnotationsMetaDataInitializer;
use Annotations\AnnotationsInitializer;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * Registering a router
 */
$di['router'] = function () {
    return require_once ROOT_URL . '/apps/config/routers.php';
};

/**
 * Register the configuration itself as a service
 */
$config = new Config(include ROOT_URL . '/apps/config/config.php');
$di->setShared('config', function () use ($config) {
    return $config;
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di['url'] = function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->baseUri);

    return $url;
};

/**
 * Start the session the first time some component request the session service
 */
$di['session'] = function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
};

/**
 * Setting up the view component
 */
$di['view'] = function () use ($config) {
    $view = new View();

    $view->registerEngines(array(
        '.volt' => function ($view, $di) use ($config) {
            $volt = new VoltEngine($view, $di);
            $volt->setOptions(array(
                'compiledPath' => $config->application->cacheDir . 'volt/',
                'compiledSeparator' => '_'
            ));

            return $volt;
        },
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ));

    return $view;
};

//Set a models manager
$di['modelsManager'] = function() {

    $eventsManager = new EventsManager();

    $modelsManager = new ModelsManager();

    $modelsManager->setEventsManager($eventsManager);

    //Attach a listener to models-manager
    $eventsManager->attach('modelsManager', new AnnotationsInitializer());

    return $modelsManager;
};

$di['annotations'] = function() {
    return new AnnotationsAdapter(array(
        'annotationsDir' => ROOT_URL . '/apps/cache/annotations/'
    ));
};

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di['modelsMetadata'] = function() {
    //Use the memory meta-data adapter in development
    $metaData = new MetaDataAdapter([
        'metaDataDir' => ROOT_URL . '/apps/cache/metadata/'
    ]);

    //Set a custom meta-data database introspection
    $metaData->setStrategy(new AnnotationsMetaDataInitializer());

    return $metaData;
};

/**
 * We register the events manager
 */
$di['dispatcher'] = function () use ($di) {

    $eventsManager = new EventsManager;

    /**
     * Check if the user is allowed to access certain action using the SecurityPlugin
     */
    //$eventsManager->attach('dispatch:beforeDispatch', new \Plugins\SecurityPlugin);

    /**
     * Handle exceptions and not-found exceptions using NotFoundPlugin
     */
    //$eventsManager->attach('dispatch:beforeException', new \Plugins\NotFoundPlugin);

    $dispatcher = new Dispatcher;
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;
};

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di['db'] = function () use ($config) {
    return new DbAdapter(array(
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname
    ));
};

/**
 * Register the flash service with custom CSS classes
 */
$di['flash'] = function () {
    $flash = new \Phalcon\Flash\Direct(array(
        'error' => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice' => 'alert alert-info',
    ));
    return $flash;
};

$di['loader'] = function () {
    return require_once ROOT_URL . '/apps/config/loaders.php';
};

