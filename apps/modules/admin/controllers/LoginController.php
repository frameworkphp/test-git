<?php
/**
 * LoginController.php Class
 * @author Phan Nguyen.
 * @email: phannguyen2020@gmail.com
 * @category: framework
 */
namespace Admin\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;

class LoginController extends Controller
{
    public function initialize()
    {
        if ($this->auth->getId() > 0 && $this->auth->getRole() == 'Administrator') {
            $this->response->redirect('admin');
        } else {
            $this->auth->remove();
        }
    }

    public function indexAction()
    {
        $formData = [];
        if ($this->request->isPost()) {
            if ($this->security->checkToken()) {
                $formData = $this->request->getPost();
                try {
                    // Authentication user login
                    $this->auth->authentication($formData);

                    $redirect = $this->dispatcher->getParam('redirect');
                    if ($redirect != '') {
                        $this->response->redirect($redirect);
                    } else {
                        $this->response->redirect('admin');
                    }
                } catch (\Exception $e) {
                    $this->flash->error('<strong>Oh snap!</strong> ' . $e->getMessage());
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
