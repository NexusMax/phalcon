<?php
namespace App\Backend\Controllers;
use Phalcon\Tag;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Criteria;
use App\Models\Menu;
use App\Models\MenuItems;
use App\Models\Users;
use App\Models\Images;
use App\Models\Pages;
use App\Models\Posts;
use App\Models\Categories;



class MenuController extends ControllerBase
{	
	public function initialize()
    {
        parent::initialize();
        $this->view->setTemplateAfter('common');
        $this->breadcrumbs->add('Меню', '/admin/' . $this->dispatcher->getControllerName() . '/index');


    }
    public function indexAction()
    {
        $params = $this->request->getQuery('');
        $sort = isset($params['sort']) ? $params['sort'] : 'id';
        $order = isset($params['order']) ? $params['order'] : 'DESC';
        $query = Criteria::fromInput($this->di, Menu::class, $params);
        $this->persistent->searchParams = $query->getParams();
        $parameters = [];
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }
        $parameters['order'] = "$sort $order";
        $paginator = new Paginator([
            "data" => Menu::find($parameters),
            "limit" => 10,
            "page" => $this->request->getQuery("page", "int")
        ]);
        $this->view->page = $paginator->getPaginate();
        $this->view->model = new Menu();
    }

    public function viewAction($id = null)
    {
        $model = Menu::findFirstById($id);
 
        if (!$model) {
            $this->flashSession->error("Меню не найдено.");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }
        
        $breadcrumbs = 'Меню: ';
       
        $this->view->title = $breadcrumbs .= $model->name;
        $this->breadcrumbs->add($breadcrumbs, null);
        $this->view->model = $model;
        $this->view->id = $id;

        $this->view->itemModel = new MenuItems();
        $params = $this->request->getQuery('');
        $sort = isset($params['sort']) ? $params['sort'] : 'id';
        $order = isset($params['order']) ? $params['order'] : 'DESC';
        $query = Criteria::fromInput($this->di, MenuItems::class, $params);
        $this->persistent->searchParams = $query->getParams();
        $parameters = [];
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }
        $parameters['order'] = "$sort $order";
        $paginator = new Paginator([
            "data" => MenuItems::find($parameters),
            "limit" => 10,
            "page" => $this->request->getQuery("page", "int")
        ]);
        $this->view->page = $paginator->getPaginate();
    }

    public function itemAction($id = null)
    {
        $model = Menu::findFirstById($id);
 
        if (!$model) {
            $this->flashSession->error("Меню не найдено.");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }
        
        $breadcrumbs = 'Меню: ';
       
        $this->view->title = $breadcrumbs .= $model->name;
        $this->breadcrumbs->add($breadcrumbs, '/admin/' . $this->dispatcher->getControllerName() . '/view/' . $model->id);

        $this->view->crudTitle = 'Добавить элемент в меню: ' . $model->name;
        $this->breadcrumbs->add($this->view->crudTitle, null);
        $modelItem = new MenuItems();
        $modelItem->menu_id = $model->id;
        Tag::setDefaults([
            'active' => 1
        ]);
        if ($this->request->isPost()) {
            $parameters =  $this->request->getPost();
            if (!$modelItem->save($parameters)) {
                $this->flash->error($modelItem->getMessages());
            } else {
                $this->flashSession->success("Элемент меню успешно добавлен!");
                return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/view/' . $model->id);
            }
        }
        
        $this->view->id = $id;
        $this->view->items = MenuItems::find(['active = 1 AND menu_id = ' . $model->id]);
        $this->view->model = $modelItem;
        echo $this->view->getPartial('' . $this->dispatcher->getControllerName() . '/_form_item');


    }

    public function viewItemAction($id = null)
    {
       $modelItem = MenuItems::findFirstById($id);
       $model = Menu::findFirstById($modelItem->menu_id);
 
        if (!$model) {
            $this->flashSession->error("Меню не найдено.");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }
        
        $breadcrumbs = 'Меню: ';
        $this->view->title = $breadcrumbs .= $model->name;
        $this->breadcrumbs->add($breadcrumbs, '/admin/' . $this->dispatcher->getControllerName() . '/view/' . $model->id);
        $this->breadcrumbs->add($modelItem->name, null);
        $this->view->model = $modelItem;
        $this->view->id = $id;
        $this->view->item = $modelItem->getTypeData($modelItem->type_id, $modelItem->item_id);
        echo $this->view->getPartial('' . $this->dispatcher->getControllerName() . '/_view_item');


    }

    public function editItemAction($id)
    {
        $modelItem = MenuItems::findFirstById($id);
        $model = Menu::findFirstById($modelItem->menu_id);
 
        if (!$model) {
            $this->flashSession->error("Меню не найдено.");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }
        
        $breadcrumbs = 'Меню: ';
       
        $this->view->title = $breadcrumbs .= $model->name;
        $this->breadcrumbs->add($breadcrumbs, '/admin/' . $this->dispatcher->getControllerName() . '/view/' . $model->id);

        $this->view->crudTitle = 'Добавить элемент в меню: ' . $model->name;
        $this->breadcrumbs->add($this->view->crudTitle, null);

        $data = $modelItem->toArray();
        $modelItem->edit = true;
        Tag::setDefaults($data);

        if ($this->request->isPost()) {
            $parameters = $this->request->getPost();
            Tag::setDefaults($parameters);
            $modelItem->assign($parameters);
            if (!$modelItem->save()) {
                $this->flash->error($modelItem->getMessages());
            } else {
                $this->flashSession->success("Элемент меню успешно обновлен!");
                return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/view/' . $model->id);
            }
        }
        

        $this->view->id = $id;
        $this->view->items = MenuItems::find(['active = 1 AND menu_id = ' . $model->id . ' AND id != ' . $modelItem->id]);
        $this->view->model = $modelItem;
        $this->view->elements = MenuItems::getTypeData($modelItem->type_id);;
        echo $this->view->getPartial('' . $this->dispatcher->getControllerName() . '/_form_item');


    }

    public function typeAction($id = null)
    {
        $this->view->disable();

        if($this->request->isAjax()){

            $type = $this->request->getPost()['type'];
            $data = MenuItems::getTypeData($type);

            if(!empty($data)){
                $html = '';
                foreach ($data as $key) {
                    $html .= '<option value="' . $key->id . '">' . $key->name . '</option>';
                }
                echo $html;
            }

        }
    }

    /**
     * Отображает форму для редактирование существующей записи
     */
    public function editAction($id = null)
    {
        $model = Menu::findFirstById($id);
        
        $this->view->crudTitle = 'Редактировать меню';
        $this->breadcrumbs->add($this->view->crudTitle, null);
        if (!$model) {
            $this->flash->error("Меню не найдена.");
            return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
        }
        $data = $model->toArray();
        $model->edit = true;
        Tag::setDefaults($data);

        if ($this->request->isPost()) {
            $parameters = $this->request->getPost();
            Tag::setDefaults($parameters);
            $model->assign($parameters);
            if (!$model->save()) {
                $this->flash->error($model->getMessages());
            } else {
               
                $this->flashSession->success("Меню успешно обновлено!");
                return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
            }
        }
        $this->view->categories = Menu::find(['active = 1']);
        $this->view->model = $model;
        echo $this->view->getPartial('' . $this->dispatcher->getControllerName() . '/_form');
    }
    /**
     * Создает продукт на основе данных, введенных в действии "new"
     */
    public function createAction()
    {
        $this->view->crudTitle = 'Добавить меню';
        $this->breadcrumbs->add($this->view->crudTitle, null);
        $model = new Menu();
        Tag::setDefaults([
            'active' => 1
        ]);
        if ($this->request->isPost()) {
            $parameters =  $this->request->getPost();
            if (!$model->save($parameters)) {
                $this->flash->error($model->getMessages());
            } else {
                $this->flashSession->success("Меню успешно добавлено!");
                return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
            }
        }
        
        $this->view->categories = Menu::find(['active = 1']);
        $this->view->model = $model;
        echo $this->view->getPartial('' . $this->dispatcher->getControllerName() . '/_form');
    }
    /**
     * Удаляет существующий продукт
     */
    public function deleteAction($id)
    {
        $model = Menu::findFirstById($id);
        if (!$model) {
            $this->flashSession->error("Меню не найдено!");
            return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
        }
        if (!$model->delete()) {
            $this->flashSession->error($model->getMessages());
        } else {
            $this->flashSession->success("Меню удалено.");
        }
        return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
    }

    public function deleteItemAction($id)
    {
        $modelItem = MenuItems::findFirstById($id);
        $model = Menu::findFirstById($modelItem->menu_id);

        if (!$modelItem) {
            $this->flashSession->error("Элемент меню не найдено!");
            return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
        }
        if (!$modelItem->delete()) {
            $this->flashSession->error($modelItem->getMessages());
        } else {
            $this->flashSession->success("Элемент меню удален.");
        }
        return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/view/' . $model->id);
    }
    
}