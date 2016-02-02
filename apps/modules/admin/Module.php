<?php
namespace Modules\Admin;

use Phalcon\DI\FactoryDefault;

class Module
{
    /**
     * Registers the module auto-loader
     */
    public function registerAutoloaders()
    {
        $loader = FactoryDefault::getDefault()->get('loader');

        $loader->registerNamespaces([
            'Admin\Controllers' => APP_URL . 'modules/admin/controllers/',
            'Plugins' => APP_URL . 'plugins/',
            'Models' => APP_URL . 'models/'
        ],true);
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
