<?php
/**
 * LoginController.php Class
 * @author Phan Nguyen.
 * @email: phannguyen2020@gmail.com
 * @category: framework
 */
namespace Admin\Controllers;

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

//                        $this->logger->name = 'access'; // Your own log name
//                        $this->logger->info(
//                            'LOG_IN_ADMINISTRATOR::'
//                            . $users->id . '::'
//                            . $users->email . '::'
//                            . $this->request->getUserAgent() . '::'
//                            . $this->request->getClientAddress()
//                        );
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
