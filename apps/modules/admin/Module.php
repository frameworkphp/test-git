<?php
namespace Modules\Admin;

use Phalcon\Loader;
use Phalcon\Mvc\Dispatcher;
use Phalcon\DI\FactoryDefault as DI;

class Module
{
    /**
     * Registers the module auto-loader
     */
    public function registerAutoloaders()
    {
        $loader = DI::getDefault()->get('loader');

        $loader->registerNamespaces([
            'Admin\Controllers' => ROOT_URL . '/apps/modules/admin/controllers/',
            'Plugins' => ROOT_URL . '/apps/plugins/',
            'Models' => ROOT_URL . '/apps/models/'
        ],true);

        //$loader->register();
    }

    /**
     * Registers the module-only services
     *
     * @param Phalcon\DI $di
     */
    public function registerServices($di)
    {
        // Registering a dispatcher
        $dispatcher = $di['dispatcher'];
        $dispatcher->setDefaultNamespace('Admin\Controllers');

        /**
         * Setting up the view component
         */
        $view = $di['view'];
        $view->setViewsDir(__DIR__ . '/views/');
    }
}
