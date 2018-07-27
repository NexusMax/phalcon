<?php
namespace App\Backend\Controllers;

use App\Backend\Controllers\ControllerBase;
use App\Models\Users;

class SessionController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Sign Up/Sign In');
        parent::initialize();

        $identity = $this->auth->getIdentity();

        if (is_array($identity)) {
            return $this->response->redirect('admin/index/index');
        } 
    }

    public function indexAction()
    {
        $this->assets->addCss('assets/admin/css/login.css');
    }

    /**
     * Finishes the active session redirecting to the index
     *
     * @return unknown
     */
    public function logoutAction()
    { 
        $this->auth->remove();
        $this->flash->success('Goodbye!');
        return $this->response->redirect('admin/session/index');
    }


    public function loginAction()
    {
        if (!$this->request->isPost()) {
            if ($this->auth->hasRememberMe()) {
                if($this->auth->loginWithRememberMe()){
                    return $this->response->redirect('admin/index/index');
                }
            }
        } else {

            if($this->auth->login([
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'remember' => $this->request->getPost('remember')
            ])){
                return $this->response->redirect('admin/index/index');
            }else{
                $this->dispatcher->forward([
                    'controller' => 'session',
                    'action' => 'index'
                ]);
                return false;
            }
        }

    }

}
