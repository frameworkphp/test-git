<?php
namespace Site\Controllers;

use \Modules\Models\Services\Services as Services;
use Phalcon\Mvc\View;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
//        try {
//            $this->view->users = Services::getService('User')->getLast();
//
//        } catch (\Exception $e) {
//            $this->flash->error($e->getMessage());
//        }
        //$this->view->setRenderLevel(View::LEVEL_MAIN_LAYOUT);
        //$this->view->pick("index/about");
        //$this->view->disable();
    }

    public function aboutAction()
    {

    }
}
