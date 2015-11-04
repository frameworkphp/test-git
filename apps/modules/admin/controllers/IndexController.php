<?php
namespace Admin\Controllers;


class IndexController extends BaseController
{
    public function indexAction()
    {

    }

    public function addAction()
    {

    }

    public function logoutAction()
    {
        $this->auth->remove();
        $this->response->redirect('admin');
    }
}
