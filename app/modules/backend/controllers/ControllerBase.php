<?php
namespace App\Backend\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;

class ControllerBase extends Controller
{
    protected function initialize()
    {
        $this->tag->appendTitle(' | Sago');
        // $this->view->setTemplateAfter('main');
        $this->breadcrumbs->add(' Главная', '/admin');
    }
    
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $controllerName = $dispatcher->getControllerName();
        $actionName = $dispatcher->getActionName();

        $identity = $this->session->get('auth');

        if (!is_array($identity)) {
            if ($this->auth->hasRememberMe() && $this->auth->loginWithRememberMe()) {
                $identity = $this->session->get('auth');
            }else{
                if($controllerName != 'session'){
                    $dispatcher->forward([
                        'controller' => 'session',
                        'action' => 'index'
                    ]);
                    return false;
                }
            }
        }

        if ($this->acl->isPrivate($controllerName, $identity['profile'])) { 

            // Check if the user have permission to the current option
            if (!$this->acl->isAllowed($identity['profile'], $controllerName, $actionName)) {

                $this->flash->notice('You don\'t have access to this module: ' . $controllerName . ':' . $actionName);

                if ($this->acl->isAllowed($identity['profile'], $controllerName, 'index')) {
                    $dispatcher->forward([
                        'controller' => $controllerName,
                        'action' => 'index'
                    ]);
                } else {
                    $dispatcher->forward([
                        'controller' => 'errors',
                        'action' => 'show401'
                    ]);
                }

                return false;
            }
        }
    }
}
