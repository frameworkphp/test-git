<?php
namespace Admin\Controllers;

use Library\Breadcrumbs;
use Phalcon\Mvc\Controller;

class BaseController extends Controller
{
    public function initialize()
    {
        $this->breadcrumbs();
        $this->tag->setTitle($this->config->appName);
        $this->tag->setTitleSeparator(' | ');
    }

    public function breadcrumbs()
    {
        $module = $this->router->getModuleName();
        $controller = $this->router->getControllerName();
        $action = $this->router->getActionName();

        $breadcrumbs = new Breadcrumbs($module);
        if ($controller) {
            $breadcrumbs->add(
                ucfirst($controller),
                $module . '/' . $controller
            );
        }

        if ($action) {
            $breadcrumbs->add(
                ucfirst($action),
                $module . '/' . $controller . '/' . $action
            );
        }

        $this->view->setVar('breadcrumbs', $breadcrumbs->generate());
    }
}
