<?php
/**
 * Services are globally registered in this file
 */

use Annotations\AnnotationsInitializer;
use Annotations\AnnotationsMetaDataInitializer;
use Library\Acl as Acl;
use Library\Auth as Auth;
use Phalcon\Annotations\Adapter\Files as AnnotationsAdapter;
use Phalcon\Config;
use Phalcon\Crypt;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\DI\FactoryDefault;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Http\Response\Cookies;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Mvc\Model\Metadata\Files as MetaDataAdapter;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Session\Adapter\Files as SessionAdapter;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * Registering a router
 */
$di['router'] = function () {
    return require_once APP_URL . 'config/routers.php';
};

/**
 * Register the configuration itself as a service
 */
$config = new Config(include APP_URL . 'config/config.php');
$di->setShared('config', function () use ($config) {
    return $config;
});

/**
 * Auto loader
 */
$di['loader'] = function () {
    return require_once APP_URL . 'config/loaders.php';
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
 * The URL component is used to generate all kind of urls in the application
 */
$di['url'] = function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->baseUri);

    return $url;
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

            $compiler = $volt->getCompiler();
            $compiler->addFilter('floor', 'floor');
            $compiler->addFunction('range', 'range');
            $compiler->addFunction('count', 'count');
            $compiler->addFunction('unserialize', 'unserialize');

            return $volt;
        },
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ));

    return $view;
};

//Set a models manager
$di['modelsManager'] = function () {
    $eventsManager = new EventsManager();
    $modelsManager = new ModelsManager();
    $modelsManager->setEventsManager($eventsManager);

    //Attach a listener to models-manager
    $eventsManager->attach('modelsManager', new AnnotationsInitializer());

    return $modelsManager;
};

$di['annotations'] = function () {
    return new AnnotationsAdapter(array(
        'annotationsDir' => APP_URL . 'cache/annotations/'
    ));
};

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di['modelsMetadata'] = function () {
    //Use the memory meta-data adapter in development
    $metaData = new MetaDataAdapter([
        'metaDataDir' => APP_URL . 'cache/metadata/'
    ]);

    //Set a custom meta-data database introspection
    $metaData->setStrategy(new AnnotationsMetaDataInitializer());

    return $metaData;
};

/**
 * Access Control List
 */
$di['acl'] = function () {
    $resources = require_once APP_URL . 'config/acl.php';
    return new Acl($resources);
};

/**
 * We register the events manager
 */
$di['dispatcher'] = function () use ($di) {
    $eventsManager = new EventsManager;

    /**
     * Handle exceptions and not-found exceptions using NotFoundPlugin
     */
    $eventsManager->attach('dispatch:beforeException', new \Plugins\NotFoundPlugin);

    $dispatcher = new Dispatcher;

    /**
     * Check if the user is allowed to access certain action using the SecurityPlugin
     */
    $eventsManager->attach('dispatch:beforeDispatch', new \Plugins\Acl);

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
        'dbname' => $config->database->dbname,
        'charset'  => 'utf8',
    ));
};

/**
 * Register the flash service with custom CSS classes
 */
$di['flash'] = function () {
    $flash = new Phalcon\Flash\Direct([
        'error' => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice' => 'alert alert-info',
    ]);
    return $flash;
};

/**
 * Register the flash service with custom CSS classes
 */
$di['flashSession'] = function () {
    $flash = new Phalcon\Flash\Session([
        'error' => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice' => 'alert alert-info',
    ]);
    return $flash;
};

/**
 * Logger
 */
$di->setShared('logger', function () use ($di) {
    return new Phalcon\Logger\Adapter\Database('errors', array(
        'db' => $di->get('db'),
        'table' => TABLE_PREFIX . 'logs'
    ));
});

$di['modelsCache'] = function () use ($config) {
    $frontCache = new Phalcon\Cache\Frontend\Data([
        'lifetime' => 60,
        'prefix' => HOST_HASH,
    ]);

    $cache = '';
    switch ($config->cache) {
        case 'file':
            $cache = new Phalcon\Cache\Backend\File($frontCache, [
                'cacheDir' => APP_URL . 'cache/model/'
            ]);
            break;
        case 'memcache':
            $cache = new Phalcon\Cache\Backend\Memcache($frontCache, [
                'host' => $config->memcache->host,
                'port' => $config->memcache->port,
            ]);
            break;
    }

    return $cache;
};

$di['cache'] = function () use ($di) {
    return $di->get('modelsCache');
};

/**
 * Access Control List
 */
$di['auth'] = function () {
    return new Auth();
};

/**
 * Init cookie
 */
$di->set('cookies', function () {
    $cookies = new Cookies();
    $cookies->useEncryption(true);

    return $cookies;
});

$di->set('crypt', function () {
    $crypt = new Crypt();
    $crypt->setMode(MCRYPT_MODE_CFB);
    $crypt->setKey('#1Pdj8$=dp?.ak//nHj1V$');

    return $crypt;
});
