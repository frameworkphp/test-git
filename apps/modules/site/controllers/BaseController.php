<?php
namespace Site\Controllers;

use Phalcon\Mvc\Controller;

class BaseController extends Controller
{
    public function initialize()
    {
        $this->tag->setTitle($this->config->appName);
        $this->tag->setTitleSeparator(' | ');
    }

    public function afterExecuteRoute()
    {
        //$this->view->setMainView('main');
        $this->view->setTemplateAfter('common');
    }
}
