<?php

namespace App\Backend\Controllers;

use Phalcon\Tag;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Criteria;
use App\Models\Users;
use App\Backend\Forms\UsersForm;

class UsersController extends ControllerBase
{	
	public function initialize()
    {
        parent::initialize();

        $this->view->setTemplateAfter('common');
        $this->breadcrumbs->add('Пользователи', '/admin/' . $this->dispatcher->getControllerName() . '/index');
    }

    public function indexAction()
    {
        $params = $this->request->getQuery('');

        $sort = isset($params['sort']) ? $params['sort'] : 'id';
        $order = isset($params['order']) ? $params['order'] : 'DESC';

        // if(isset($params['created_at'])){
        //     unset($params['created_at']);
        // }
        // if(isset($params['updated_at'])){
        //     unset($params['updated_at']);
        // }

        $query = Criteria::fromInput($this->di, Users::class, $params);
        $this->persistent->searchParams = $query->getParams();

        $parameters = [];
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }
        $parameters['order'] = "$sort $order";

        if(isset($params['created_at'])){
            $parameters['conditions'] = $parameters['conditions'] . ' created_at LIKE "%' . $this->request->getQuery('created_at') . '%"';
        }
        if(isset($params['updated_at'])){
            $parameters['conditions'] = $parameters['conditions'] . ' updated_at LIKE "%' . $this->request->getQuery('updated_at') . '%"';
        }

        
        // echo '<pre>';
        // print_r($query->getParams());
        // die;
        $paginator = new Paginator([
            "data" => Users::find($parameters),
            "limit" => 10,
            "page" => $this->request->getQuery("page", "int")
        ]);

        $this->view->model = new Users();
        $this->view->page = $paginator->getPaginate();
    }


    public function viewAction($id = null)
    {

        $user = Users::findFirstById($id);

        if (!$user) {
            $this->flash->error("Пользователь не найден.");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }

        $this->breadcrumbs->add('Пользователь: ' . $user->middlename . ' ' . $user->name . ' ' . $user->surname , null);

        $this->view->user = $user;
    }

    /**
     * Отображает форму для редактирование существующего продукта
     */
    public function editAction($id = null)
    {
        $user = Users::findFirstById($id);
        $this->view->crudTitle = 'Редактировать пользователя';
        $this->breadcrumbs->add($this->view->crudTitle, null);

        if (!$user) {
            $this->flash->error("Пользователь не найден.");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }

        $form = new UsersForm($user, [
            'edit' => true
        ]);


        if ($this->request->isPost()) {

            if ($form->isValid($this->request->getPost()) == false) {

                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
                
            } else {
                $parameters = $this->request->getPost();

                foreach ($parameters as $key => $value) {
                    if(!is_array($value) && (trim($value) === '')){
                        $parameters[$key] = null;
                    }
                }

                $user->assign($parameters);

                if (!$user->save()) {
                    $this->flash->error($user->getMessages());
                } else {

                    $this->flash->success("Пользователь успешно обновлен!");
                    $this->flashSession->success("Пользователь успешно обновлен!");
                    return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
                }
            }
        }

        $this->view->form = $form;
        $this->view->user = $user;
        echo $this->view->getPartial('' . $this->dispatcher->getControllerName() . '/_form');
    }

    /**
     * Создает продукт на основе данных, введенных в действии "new"
     */
    public function createAction()
    {
        $this->view->crudTitle = 'Добавить пользователя';
        $this->breadcrumbs->add($this->view->crudTitle, null);

        $form = new UsersForm();

        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost()) == false) {
                
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
                
            } else {
                $parameters = $this->request->getPost();

                foreach ($parameters as $key => $value) {
                    if(trim($value) === ''){
                        $parameters[$key] = null;
                    }
                }

                $user = new Users($parameters);

                if (!$user->save()) {
                    $this->flash->error($user->getMessages());
                } else {

                    $this->flashSession->success("Пользователь успешно добавлен!");
                    $this->flash->success("Пользователь успешно добавлен!");
                    return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
                }
            }
        }

        $this->view->form = $form;
        echo $this->view->getPartial('' . $this->dispatcher->getControllerName() . '/_form');
    }


    /**
     * Удаляет существующий продукт
     */
    public function deleteAction($id)
    {
        $user = Users::findFirstById($id);

        if (!$user) {
            $this->flashSession->error("Пользователь не найден!");

            return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
        }

        if (!$user->delete()) {
            $this->flash->error($user->getMessages());
        } else {
            $this->flashSession->success("Пользователь удалён.");
        }

        return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
    }


}

