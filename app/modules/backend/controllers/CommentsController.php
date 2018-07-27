<?php
namespace App\Backend\Controllers;
use Phalcon\Tag;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Criteria;
use App\Models\Comments;
use App\Models\Posts;
use App\Models\Categories;
use App\Models\Users;
use App\Models\Images;
use App\Models\Tags;
use App\Models\PostHasTag;


class CommentsController extends ControllerBase
{	
	public function initialize()
    {
        parent::initialize();
        $this->view->setTemplateAfter('common');
        $this->breadcrumbs->add('Комментарии', '/admin/' . $this->dispatcher->getControllerName() . '/index');
    }
    public function indexAction()
    {
        $params = $this->request->getQuery('');
        $sort = isset($params['sort']) ? $params['sort'] : 'id';
        $order = isset($params['order']) ? $params['order'] : 'DESC';
        $query = Criteria::fromInput($this->di, Comments::class, $params);
        $this->persistent->searchParams = $query->getParams();
        $parameters = [];
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }
        $parameters['order'] = "$sort $order";
        $paginator = new Paginator([
            "data" => Comments::find($parameters),
            "limit" => 10,
            "page" => $this->request->getQuery("page", "int")
        ]);
        $this->view->page = $paginator->getPaginate();
        $this->view->model = new Comments();
    }

    public function viewAction($id = null)
    {
        $model = Comments::findFirstById($id);
 
        if (!$model) {
            $this->flashSession->error("Комментраий не найден.");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }
        
        $breadcrumbs = 'Комментарий: ';

        $this->view->title = $breadcrumbs .= $model->post->name;
        $this->breadcrumbs->add($breadcrumbs, null);
        $this->view->model = $model;
    }
    /**
     * Отображает форму для редактирование существующей записи
     */
    public function editAction($id = null)
    {
        $model = Comments::findFirstById($id);
        
        $this->view->crudTitle = 'Редактировать комментарий';
        $this->breadcrumbs->add($this->view->crudTitle, null);
        if (!$model) {
            $this->flash->error("Комментарий не найдена.");
            return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
        }
        $data = $model->toArray();
        $model->edit = true;
        Tag::setDefaults($data);

        if ($this->request->isPost()) {
            $parameters = $this->request->getPost();
            Tag::setDefaults($parameters);
            $model->assign($parameters);
            if (!$model->save() ) {
                $this->flash->error($model->getMessages());
            } else {
                
                $this->flashSession->success("Комментарий успешно обновлен!");
                return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
            }
        }



        $this->view->posts = Posts::find(['active = 1']);
        $this->view->users = Users::find(['active = 1 AND banned = 0']);
        $this->view->comments = Comments::find(['active = 1 AND post_id = ' . $model->post_id . '']);
        $this->view->model = $model;
        echo $this->view->getPartial('' . $this->dispatcher->getControllerName() . '/_form');
    }
    /**
     * Создает продукт на основе данных, введенных в действии "new"
     */
    public function createAction()
    {
        $this->view->crudTitle = 'Добавить комментарий';
        $this->breadcrumbs->add($this->view->crudTitle, null);
        $model = new Comments();

        Tag::setDefaults([
            'active' => 1
        ]);
        if ($this->request->isPost()) {
            $parameters =  $this->request->getPost();
           
            if (!$model->save($parameters)) {
                $this->flash->error($model->getMessages());
            } else {
                
                $this->flashSession->success("Комментарий успешно добавлен!");
                return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
            }
        }
        
    
        $this->view->posts = Posts::find(['active = 1']);
        $this->view->users = Users::find(['active = 1', 'banned = 0']);
        $this->view->comments = Comments::find(['active = 1']);
        $this->view->model = $model;
        echo $this->view->getPartial('' . $this->dispatcher->getControllerName() . '/_form');
    }
    /**
     * Удаляет существующий продукт
     */
    public function deleteAction($id)
    {
        $model = Comments::findFirstById($id);
        if (!$model) {
            $this->flashSession->error("Комментарий не найден!");
            return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
        }
        if (!$model->delete()) {
            $this->flashSession->error($model->getMessages());
        } else {
            $this->flashSession->success("Комментарий удалён.");
        }
        return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
    }
}