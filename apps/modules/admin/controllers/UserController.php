<?php
/**
 * UserController.php 12/10/2015
 * ----------------------------------------------
 *
 * @author      Phan Nguyen <phannguyen2020@gmail.com>
 * @copyright   Copyright (c) 2015, framework
 *
 * ----------------------------------------------
 * All Rights Reserved.
 * ----------------------------------------------
 */

namespace Admin\Controllers;

use Models\User;

class UserController extends BaseController
{
    public function indexAction()
    {

    }

    public function addAction()
    {
        $formData = array();
        if ($this->request->isPost()) {
            if ($this->security->checkToken()) {
                $formData = $this->request->getPost();
                $myUser = User::findFirstByEmail($formData['email']);
                if (!$myUser) {
                    $user = new User();
                    $user->name = $formData['name'];
                    $user->email = $formData['email'];
                    $user->password = $this->security->hash($formData['password']);
                    $user->gender = $formData['gender'];
                    $user->dateCreate = time();

                    if ($user->create()) {
                        $this->flash->success('<strong>Well done!</strong> Add user successfully');
                    } else {
                        $this->flash->error('<strong>Oh snap!</strong> System error.');
                    }
                } else {
                    $this->flash->error('<strong>Oh snap!</strong> User already exists.');
                }
            }
        }

        $this->view->setVars([
            'formData' => $formData
        ]);
    }
}
