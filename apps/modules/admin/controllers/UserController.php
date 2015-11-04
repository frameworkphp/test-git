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
    protected $recordPerPage = 2;

    public function indexAction()
    {
        $page = (int)$this->request->getQuery('page', 'int', 1);
        $keyword = $this->request->getQuery('keyword', 'string', '');
        $sortBy = $this->request->getQuery('sortby', 'string', '');
        $sortType = $this->request->getQuery('sorttype', 'string', '');

        $currentUrl = substr($this->router->getRewriteUri(), 1);
        $keywordIn = ['name', 'email'];
        $parameter = [
            'keyword' => $keyword,
            'keywordIn' => $keywordIn
        ];
        $users = User::getUsers($parameter, '*', $this->recordPerPage, $page, $sortBy, $sortType);

        $queryUrl = '';
        if ($keyword != '') {
            $queryUrl .= ($queryUrl == '' ? '?' : '&') . 'keyword=' . $keyword;
        }

        // Always abort sortBy and sortType
        $orderUrl = $currentUrl . $queryUrl . ($queryUrl == '' ? '?' : '&');
        if ($sortBy != '') {
            $queryUrl .= ($queryUrl == '' ? '?' : '&') . 'sortby=' . $sortBy;
        }

        if ($sortType != '') {
            $queryUrl .= ($queryUrl == '' ? '?' : '&') . 'sorttype=' . $sortType;
        }

        $paginateUrl = $currentUrl . $queryUrl . ($queryUrl == '' ? '?' : '&');

        $this->view->setVars([
            'keyword' => $keyword,
            'sortBy' => $sortBy,
            'sortType' => $sortType,
            'users' => $users,
            'orderUrl' => $orderUrl,
            'pagination' => $users,
            'paginateUrl' => $paginateUrl
        ]);
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
                            $this->flash->success('Add user successfully');
                        } else {
                            $error = $userModel->getMessages();
                        }
                    } else {
                        $error[] = 'User already exists.';
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
