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
        $error = [];
        $formData = [];
        if ($this->request->isPost()) {
            if ($this->security->checkToken()) {
                $formData = $this->request->getPost();
                if ($this->addUserValidator($formData, $error)) {
                    $myUser = User::findFirstByEmail($formData['email']);
                    if (!$myUser) {
                        $userModel = new User();
                        $userModel->name = $formData['name'];
                        $userModel->email = $formData['email'];
                        $userModel->password = $this->security->hash($formData['password']);
                        $userModel->gender = $formData['gender'];
                        $userModel->role = $formData['role'];

                        if ($userModel->create()) {
                            $this->flash->success('<strong>Well done!</strong> Add user successfully');
                        } else {
                            $error = $userModel->getMessages();
                        }
                    } else {
                        $error[] = '<strong>Oh snap!</strong> User already exists.';
                    }
                }
            }

            if (!empty($error)) {
                $this->flash->outputMessage('error', $error);
            }
        }

        $this->view->setVars([
            'formData' => $formData,
            'roles' => User::$roles
        ]);
    }

    public function addUserValidator($formData, &$error)
    {
        $pass = true;

        if ($formData['name'] == '') {
            $error[] = 'Name is required';
            $pass = false;
        }

        if ($formData['email'] == '') {
            $error[] = 'Email is required';
            $pass = false;
        }

        return $pass;
    }
}
