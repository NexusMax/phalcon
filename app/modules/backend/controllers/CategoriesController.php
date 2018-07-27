<?php
namespace App\Backend\Controllers;
use Phalcon\Tag;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Criteria;
use App\Models\Categories;
use App\Models\Users;
use App\Models\Images;


class CategoriesController extends ControllerBase
{	
	public function initialize()
    {
        parent::initialize();
        $this->view->setTemplateAfter('common');
        $this->breadcrumbs->add('Категории', '/admin/' . $this->dispatcher->getControllerName() . '/index');
    }
    public function indexAction()
    {
        $params = $this->request->getQuery('');
        $sort = isset($params['sort']) ? $params['sort'] : 'id';
        $order = isset($params['order']) ? $params['order'] : 'DESC';
        $query = Criteria::fromInput($this->di, Categories::class, $params);
        $this->persistent->searchParams = $query->getParams();
        $parameters = [];
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }
        $parameters['order'] = "$sort $order";
        $paginator = new Paginator([
            "data" => Categories::find($parameters),
            "limit" => 10,
            "page" => $this->request->getQuery("page", "int")
        ]);
        $this->view->page = $paginator->getPaginate();
        $this->view->model = new Categories();
    }
    public function viewAction($id = null)
    {
        $model = Categories::findFirstById($id);
 
        if (!$model) {
            $this->flashSession->error("Категория не найдена.");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }
        
        $breadcrumbs = 'Запись: ';
       
        $this->view->title = $breadcrumbs .= $model->name;
        $this->breadcrumbs->add($breadcrumbs, null);
        $this->view->model = $model;
    }
    /**
     * Отображает форму для редактирование существующей записи
     */
    public function editAction($id = null)
    {
        $model = Categories::findFirstById($id);
        
        $this->view->crudTitle = 'Редактировать категорию';
        $this->breadcrumbs->add($this->view->crudTitle, null);
        if (!$model) {
            $this->flash->error("Категория не найдена.");
            return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
        }
        $data = $model->toArray();
        $model->edit = true;
        Tag::setDefaults($data);

        if ($this->request->isPost()) {
            $parameters = $this->request->getPost();
            Tag::setDefaults($parameters);
            $model->assign($parameters);
            if (!$model->save() || !$model->upload($this->request)) {
                $this->flash->error($model->getMessages());
            } else {
               
                $this->flashSession->success("Категория успешно обновлена!");
                return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
            }
        }
        $this->view->categories = Categories::find(['active = 1']);
        $this->view->model = $model;
        echo $this->view->getPartial('' . $this->dispatcher->getControllerName() . '/_form');
    }
    /**
     * Создает продукт на основе данных, введенных в действии "new"
     */
    public function createAction()
    {
        $this->view->crudTitle = 'Добавить категорию';
        $this->breadcrumbs->add($this->view->crudTitle, null);
        $model = new Categories();
        Tag::setDefaults([
            'active' => 1
        ]);
        if ($this->request->isPost()) {
            $parameters =  $this->request->getPost();
            if (!$model->save($parameters) || !$model->upload($this->request)) {
                $this->flash->error($model->getMessages());
            } else {
                $this->flashSession->success("Категория успешно добавлена!");
                return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
            }
        }
        
        $this->view->categories = Categories::find(['active = 1']);
        $this->view->model = $model;
        echo $this->view->getPartial('' . $this->dispatcher->getControllerName() . '/_form');
    }
    /**
     * Удаляет существующий продукт
     */
    public function deleteAction($id)
    {
        $model = Categories::findFirstById($id);
        if (!$model) {
            $this->flashSession->error("Категория не найдена!");
            return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
        }
        if (!$model->delete()) {
            $this->flashSession->error($model->getMessages());
        } else {
            $this->flashSession->success("Категория удалёна.");
        }
        return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
    }
    public function removeimageAction()
    {
        $this->view->disable();
        if($this->request->isAjax()){
            $id = $this->request->getPost()['id']; 
            $model = Categories::findFirstById($id);
            Images::deleteImage($model->imgUploadDir . $model->logo);
            $model->logo = '';
            echo $model->save();
        }
    }
}