<?php
namespace Admin\Controllers;

use Phalcon\Mvc\Controller;

class BaseController extends Controller
{
    public function initialize()
    {
        if (!$this->session->get('Auth')) {
            return $this->dispatcher->forward([
                'module' => 'admin',
                'controller' => 'login',
                'action' => 'index'
            ]);
        }
    }
}
