<?php
namespace Admin\Controllers;


class IndexController extends BaseController
{
    public function indexAction()
    {
        $this->tag->setTitle('Welcome to PHP Framework');
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
