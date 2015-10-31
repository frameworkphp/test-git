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
        $this->session->destroy();
        $this->response->redirect('admin');
    }
}
