<?php
/**
 * ErrorsController.php Class
 * @author Phan Nguyen.
 * @email: phannguyen2020@gmail.com
 * @category: framework
 */

namespace Site\Controllers;


class ErrorController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Oops!');
    }

    public function show404Action()
    {

    }

    public function show401Action()
    {

    }

    public function show500Action()
    {

    }
}
