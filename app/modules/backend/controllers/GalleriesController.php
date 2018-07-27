<?php
namespace App\Backend\Controllers;
use Phalcon\Tag;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Criteria;
use App\Models\Galleries;
use App\Models\GalleryItem;
use App\Models\Users;
use App\Models\Images;


class GalleriesController extends ControllerBase
{	
	public function initialize()
    {
        parent::initialize();
        $this->view->setTemplateAfter('common');
        $this->breadcrumbs->add('Галереи', '/admin/' . $this->dispatcher->getControllerName() . '/index');


    }
    public function indexAction()
    {
        $params = $this->request->getQuery('');
        $sort = isset($params['sort']) ? $params['sort'] : 'id';
        $order = isset($params['order']) ? $params['order'] : 'DESC';
        $query = Criteria::fromInput($this->di, Galleries::class, $params);
        $this->persistent->searchParams = $query->getParams();
        $parameters = [];
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }
        $parameters['order'] = "$sort $order";
        $paginator = new Paginator([
            "data" => Galleries::find($parameters),
            "limit" => 10,
            "page" => $this->request->getQuery("page", "int")
        ]);
        $this->view->page = $paginator->getPaginate();
        $this->view->model = new Galleries();
    }
    public function viewAction($id = null)
    {
        $model = Galleries::findFirstById($id);
 
        if (!$model) {
            $this->flashSession->error("Галерея не найдена.");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }
        
        $breadcrumbs = 'Галерея: ';
       
        $this->view->title = $breadcrumbs .= $model->name;
        $this->breadcrumbs->add($breadcrumbs, null);
        $this->view->model = $model;
    }
    /**
     * Отображает форму для редактирование существующей записи
     */
    public function editAction($id = null)
    {
        $model = Galleries::findFirstById($id);
        
        $this->view->crudTitle = 'Редактировать галерею';
        $this->breadcrumbs->add($this->view->crudTitle, null);
        if (!$model) {
            $this->flash->error("Галерея не найдена.");
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
               
                $this->flashSession->success("Галерея успешно обновлена!");
                return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
            }
        }
        $this->view->categories = Galleries::find(['active = 1']);
        $this->view->model = $model;
        echo $this->view->getPartial('' . $this->dispatcher->getControllerName() . '/_form');
    }
    /**
     * Создает продукт на основе данных, введенных в действии "new"
     */
    public function createAction()
    {
        $this->view->crudTitle = 'Добавить галерею';
        $this->breadcrumbs->add($this->view->crudTitle, null);
        $model = new Galleries();
        Tag::setDefaults([
            'active' => 1
        ]);
        if ($this->request->isPost()) {
            $parameters = $this->request->getPost();

            if (!$model->save($parameters) || !$model->upload($this->request)) {
                $this->flash->error($model->getMessages());
            } else {
                $this->flashSession->success("Галерея успешно добавлена!");
                return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
            }
        }
        
        $this->view->categories = Galleries::find(['active = 1']);
        $this->view->model = $model;
        echo $this->view->getPartial('' . $this->dispatcher->getControllerName() . '/_form');
    }
    /**
     * Удаляет существующий продукт
     */
    public function deleteAction($id)
    {
        $model = Galleries::findFirstById($id);
        if (!$model) {
            $this->flashSession->error("Галерея не найдена!");
            return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
        }
        if (!$model->delete()) {
            $this->flashSession->error($model->getMessages());
        } else {
            $this->flashSession->success("Галерея удалёна.");
        }
        return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
    }

    public function deleteitemAction()
    {
        $this->view->disable();

        if($this->request->isAjax()){
            $id = $this->request->getPost()['id'];
            $gallery = new Galleries;
            $gallery->deleteItem($id);
        }
    }
    
}