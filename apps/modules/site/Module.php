<?php
namespace Modules\Site;

use Phalcon\Loader;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\Dispatcher;

class Module implements ModuleDefinitionInterface
{
    /**
     * Registers the module auto-loader
     */
    public function registerAutoloaders()
    {

        $loader = new Loader();

        $loader->registerNamespaces(array(
            'Site\Controllers' => ROOT_URL . '/apps/modules/site/controllers/',
            'Plugins' => ROOT_URL . '/apps/plugins/',
            'Modules\Models\Entities' => ROOT_URL . '/apps/models/entities/',
            'Modules\Models\Services' => ROOT_URL . '/apps/models/services/',
            'Modules\Models\Repositories' => ROOT_URL . '/apps/models/repositories/'
        ));

        $loader->register();
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
        $dispatcher->setDefaultNamespace('Site\Controllers');

        /**
         * Setting up the view component
         */
        $view = $di['view'];
        $view->setViewsDir(__DIR__ . '/views/');
    }
}
