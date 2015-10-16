<?php
/**
 * routers.php Class
 * @author Phan Nguyen.
 * @email: phannguyen2020@gmail.com
 * @category: framework
 */

use Phalcon\Mvc\Router;

$router = new Router();

$router->setDefaultModule('site');

//$router->setDefaultNamespace('Modules\Site\Controllers');

$router->add('/admin', array(
    'module' => 'admin'
));

$router->add('/admin/:controller/:action/:params', array(
    'module' => 'admin',
    'controller' => 1,
    'action' => 2,
    'params' => 3,
));

$router->add('/admin/:controller', array(
    'module' => 'admin',
    'controller' => 1
));

//$router->setUriSource(\Phalcon\Mvc\Router::URI_SOURCE_SERVER_REQUEST_URI);

$router->removeExtraSlashes(true);

return $router;
