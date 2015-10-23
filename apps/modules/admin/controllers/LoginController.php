<?php
/**
 * LoginController.php Class
 * @author Phan Nguyen.
 * @email: phannguyen2020@gmail.com
 * @category: framework
 */
namespace Admin\Controllers;

use Models\Logs;
use Models\User;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Controller;

class LoginController extends Controller
{
    public function indexAction()
    {
        $formData = array();
        if ($this->request->isPost()) {
            if ($this->security->checkToken()) {
                $formData = $this->request->getPost();
                $users = User::findFirstByEmail($formData['femail']);
                if ($users) {
                    if ($this->security->checkHash($formData['fpassword'], $users->password)) {
                        // create session for user
                        $this->session->set('Auth', $users);

                        // Handel write log
                        $infoLog = [
                            'user_id' => $users->id,
                            'email' => $users->email,
                            'user_agent' => $this->request->getUserAgent(),
                            'ip_address' => $this->request->getClientAddress()
                        ];
                        Logs::log('Login', serialize($infoLog), Logs::INFO);

                        $redirect = $this->dispatcher->getParam('redirect');
                        if ($redirect != '') {
                            $this->response->redirect($redirect);
                        } else {
                            $this->response->redirect('admin');
                        }
                    } else {
                        $this->flash->error('<strong>Oh snap!</strong> Password not match.');
                    }
                } else {
                    $this->flash->error('<strong>Oh snap!</strong> Email not exists.');
                }
            }
        }

        $this->view->setVars([
            'formData' => $formData
        ]);

        // Show only view relate to this action
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }
}
