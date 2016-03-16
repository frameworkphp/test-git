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
    protected $recordPerPage = 10;

    public function indexAction()
    {
        $page = (int)$this->request->getQuery('page', 'int', 1);
        $keyword = $this->request->getQuery('q', 'string', '');
        $sort = $this->request->getQuery('sort', 'string', '');
        $dir = $this->request->getQuery('dir', 'string', '');

        // Create url dynamic
        $currentUrl = substr($this->router->getRewriteUri(), 1);
        $queryUrl = '';
        if ($keyword != '') {
            $queryUrl .= ($queryUrl == '' ? '?' : '&') . 'q=' . str_replace(' ', '+', $keyword);
        }

        // Add keyword parameter
        $keywordIn = ['name', 'email'];
        $parameter = [
            'keyword' => $keyword,
            'keywordIn' => $keywordIn
        ];

        // Get and add filter in parameter
        $role = $this->request->getQuery('role', 'string', '');
        $status = $this->request->getQuery('status', 'int', '');

        if ($role != '') {
            $parameter['role'] = $role;
            $queryUrl .= ($queryUrl == '' ? '?' : '&') . 'role=' . $role;
        }

        if ($status != '') {
            $parameter['status'] = $status;
            $queryUrl .= ($queryUrl == '' ? '?' : '&') . 'status=' . $status;
        }

        // Get list users
        $users = User::getUsers($parameter, '*', $this->recordPerPage, $page, $sort, $dir);

        // Always abort sortBy and sortType
        $orderUrl = $currentUrl . $queryUrl . ($queryUrl == '' ? '?' : '&');

        if ($sort != '') {
            $queryUrl .= ($queryUrl == '' ? '?' : '&') . 'sort=' . $sort;
        }

        if ($dir != '') {
            $queryUrl .= ($queryUrl == '' ? '?' : '&') . 'dir=' . $dir;
        }

        $paginateUrl = $currentUrl . $queryUrl . ($queryUrl == '' ? '?' : '&');

        $this->view->setVars([
            'parameter' => $parameter,
            'sort' => $sort,
            'dir' => $dir,
            'users' => $users,
            'orderUrl' => $orderUrl,
            'pagination' => $users,
            'paginateUrl' => $paginateUrl,
            'roles' => User::$roles,
            'status' => User::$statusName
        ]);
        $this->tag->prependTitle('Manager user');
    }

    public function addAction()
    {
        $formData = [];
        if ($this->request->isPost()) {
            if ($this->security->checkToken()) {
                $formData = $this->request->getPost();
                $userModel = new User();
                $userModel->name = $formData['name'];
                $userModel->email = $formData['email'];
                $userModel->password = $this->security->hash($formData['password']);
                $userModel->gender = $formData['gender'];
                $userModel->role = $formData['role'];
                $userModel->status = $formData['status'];

                if ($userModel->create()) {
                    $formData = [];
                    $this->flash->success('Add user successfully');
                } else {
                    $this->flash->outputMessage('error', $userModel->getMessages());
                }
            }
        }

        $this->view->setVars([
            'formData' => $formData,
            'roles' => User::$roles,
            'status' => User::$statusName
        ]);
        $this->tag->prependTitle('Add user');
    }

    public function editAction($id)
    {
        $user = User::getUserById($id);

        if ($this->request->isPost()) {
            if ($this->security->checkToken()) {
                $formData = $this->request->getPost();

                $user->name = $formData['name'];
                $user->gender = $formData['gender'];
                $user->role = $formData['role'];
                $user->status = $formData['status'];

                if ($formData['password'] != '') {
                    $user->password = $this->security->hash($formData['password']);
                }

                if ($user->update()) {
                    $this->flashSession->success('User ' . $user->name . ' updated.');

                    if ($formData['redirect'] != '') {
                        $redirect = $formData['redirect'];
                    } else {
                        $redirect = 'admin/user';
                    }
                    $this->response->redirect($redirect . '#_' . $user->id);
                } else {
                    $this->flash->outputMessage('error', $user->getMessages());
                }
            }
        }

        $this->view->setVars([
            'user' => $user,
            'roles' => User::$roles,
            'status' => User::$statusName,
            'redirect' => $this->request->getHTTPReferer()
        ]);
        $this->tag->prependTitle('Edit user');
    }

    public function deleteAction($id)
    {
        $httpRefer = $this->request->getHTTPReferer();
        if ($httpRefer) {
            $user = User::getUserById($id);

            if ($user->delete()) {
                // if deleting the account itself is performed logout
                if ($user->id == $this->auth->getId()) {
                    $this->auth->remove();
                } else {
                    $this->flashSession->success('Delete user ' . $user->name . ' successfully');
                }
            } else {
                $this->flashSession->outputMessage('error', $user->getMessages());
            }
        }

        return $this->response->redirect('admin/user');
    }

    public function deletesAction()
    {
        if ($this->request->isPost()) {
            $ids = $this->request->getPost('cid');
            if (count($ids) > 0) {
                $users = User::find('id IN (' . implode(',', $ids) . ')');

                $userDeleted = [];
                foreach ($users as $user) {
                    if ($user->delete()) {
                        $userDeleted[] = $user->name;
                    }
                }

                if (count($userDeleted) > 0) {
                    $this->flashSession->success('Users ' . implode(', ', $userDeleted) . ' deleted.');
                } else {
                    $this->flashSession->error('Users need delete not found.');
                }
            }
        }

        return $this->response->redirect('admin/user');
    }
}
