<?php
/**
 * Acl.php 26/10/2015
 * ----------------------------------------------
 *
 * @author      Phan Nguyen <phannguyen2020@gmail.com>
 * @copyright   Copyright (c) 2015, framework
 *
 * ----------------------------------------------
 * All Rights Reserved.
 * ----------------------------------------------
 */

namespace Plugins;

use Models\User;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;

class Acl extends Plugin
{
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        $role = $this->getRole();

        $module = $dispatcher->getModuleName();
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        $acl = $this->acl->getAcl();
        $resource = $module . '/' . $controller;

        if ($acl->isResource($resource)) {
            if (!$acl->isAllowed($role, $resource, $action)) {
                $this->notPermission($dispatcher);
            }
        } else {
            $this->resourceNotFound($resource);
        }
    }

    private function getRole()
    {
        $auth = $this->session->get('Auth');
        if (!$auth) {
            $role = 'guest';
        } else {
            $role = User::getRoleById($auth->id);
        }
        return $role;
    }

    private function notPermission(Dispatcher $dispatcher)
    {
        $dispatcher->forward([
            'module' => 'site',
            'controller' => 'error',
            'action' => 'show404'
        ]);
    }

    private function resourceNotFound($resource)
    {
        $this->view->setViewsDir(ROOT_URL . '/apps/modules/site/views/');
        $this->view->setPartialsDir('');
        $this->view->message = "Acl resource <b>$resource</b> in <b>/app/config/acl.php</b> not exists";
        $this->view->partial('error/show404');
        $response = new Phalcon\Http\Response();
        $response->setHeader(404, 'Not Found');
        $response->sendHeaders();
        echo $response->getContent();
        exit;
    }

    private function redirect($url, $code = 302)
    {
        switch ($code) {
            case 301 :
                header('HTTP/1.1 301 Moved Permanently');
                break;
            case 302 :
                header('HTTP/1.1 302 Moved Temporarily');
                break;
        }
        header('Location: ' . $url);
        exit;
    }
}
