<?php
namespace Site\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    public function afterExecuteRoute()
    {
        $this->view->setMainView('main');
        $this->view->setTemplateAfter('common');
    }
}
