<?php
namespace App\Backend\Controllers;
use Phalcon\Tag;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Criteria;
use App\Models\Posts;
use App\Models\Categories;
use App\Models\Users;
use App\Models\Images;
use App\Models\Tags;
use App\Models\PostHasTag;


class PostsController extends ControllerBase
{	
	public function initialize()
    {
        parent::initialize();
        $this->view->setTemplateAfter('common');
        $this->breadcrumbs->add('Записи', '/admin/' . $this->dispatcher->getControllerName() . '/index');
    }
    public function indexAction()
    {
        $params = $this->request->getQuery('');
        $sort = isset($params['sort']) ? $params['sort'] : 'id';
        $order = isset($params['order']) ? $params['order'] : 'DESC';
        $query = Criteria::fromInput($this->di, Posts::class, $params);
        $this->persistent->searchParams = $query->getParams();
        $parameters = [];
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }
        $parameters['order'] = "$sort $order";
        $paginator = new Paginator([
            "data" => Posts::find($parameters),
            "limit" => 10,
            "page" => $this->request->getQuery("page", "int")
        ]);
        $this->view->page = $paginator->getPaginate();
        $this->view->model = new Posts();
    }
    public function viewAction($id = null)
    {
        $model = Posts::findFirstById($id);
 
        if (!$model) {
            $this->flashSession->error("Запись не найдена.");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }
        
        $breadcrumbs = 'Запись: ';
        if($model->category->name){
        	$breadcrumbs .= '(' . $model->category->name . ') ';
        }
        $this->view->title = $breadcrumbs .= $model->name;
        $this->breadcrumbs->add($breadcrumbs, null);
        $this->view->model = $model;
    }
    /**
     * Отображает форму для редактирование существующей записи
     */
    public function editAction($id = null)
    {
        $model = Posts::findFirstById($id);
        
        $this->view->crudTitle = 'Редактировать запись';
        $this->breadcrumbs->add($this->view->crudTitle, null);
        if (!$model) {
            $this->flash->error("Запись не найдена.");
            return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
        }
        $data = $model->toArray();
        $data['tags'] = implode(',', array_column($model->tags->toArray(), 'name'));
        $model->edit = true;
        Tag::setDefaults($data);

        if ($this->request->isPost()) {
            $parameters = $this->request->getPost();
            Tag::setDefaults($parameters);
            $model->assign($parameters);
            if (!$model->save() || !$model->upload($this->request)) {
                $this->flash->error($model->getMessages());
            } else {

                Tags::query()->where('post_id = ' . $model->id)->execute()->delete();

                if(!empty($parameters['tags'])){
                    $tags = explode(',', $parameters['tags']);
                    foreach($tags as $key){
                        $tag = new Tags();
                        $tag->create([
                            'post_id' => $model->id,
                            'name' => $key,
                            'active' => 1,
                            'alias' => $tag->str2url($key)
                        ]);
                    }
                }
                
                $this->flashSession->success("Запись успешно обновлена!");
                return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
            }
        }
        $this->view->categories = Categories::find(['active = 1']);
        $this->view->users = Users::find(['active = 1', 'banned = 0']);
        $this->view->model = $model;
        echo $this->view->getPartial('' . $this->dispatcher->getControllerName() . '/_form');
    }
    /**
     * Создает продукт на основе данных, введенных в действии "new"
     */
    public function createAction()
    {
        $this->view->crudTitle = 'Добавить запись';
        $this->breadcrumbs->add($this->view->crudTitle, null);
        $model = new Posts();

        $tag = new Tags();

        Tag::setDefaults([
            'active' => 1
        ]);
        if ($this->request->isPost()) {
            $parameters =  $this->request->getPost();
           
            if (!$model->save($parameters) || !$model->upload($this->request)) {
                $this->flash->error($model->getMessages());
            } else {

                if(!empty($parameters['tags'])){
                    $tags = explode(',', $parameters['tags']);
                    foreach($tags as $key){
                        $tag = new Tags();
                        $tag->create([
                            'post_id' => $model->id,
                            'name' => $key,
                            'active' => 1,
                            'alias' => $tag->str2url($key)
                        ]);
                    }
                }
                
                $this->flashSession->success("Запись успешно добавлена!");
                return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
            }
        }
        
    
        $this->view->categories = Categories::find(['active = 1']);
        $this->view->users = Users::find(['active = 1', 'banned = 0']);
        $this->view->model = $model;
        echo $this->view->getPartial('' . $this->dispatcher->getControllerName() . '/_form');
    }
    /**
     * Удаляет существующий продукт
     */
    public function deleteAction($id)
    {
        $model = Posts::findFirstById($id);
        if (!$model) {
            $this->flashSession->error("Запись не найдена!");
            return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
        }
        if (!$model->delete()) {
            $this->flashSession->error($model->getMessages());
        } else {
            $this->flashSession->success("Запись удалёна.");
        }
        return $this->response->redirect('admin/' . $this->dispatcher->getControllerName() . '/index');
    }
    public function removeimageAction()
    {
        $this->view->disable();
        if($this->request->isAjax()){
            $id = $this->request->getPost()['id']; 
            $model = Posts::findFirstById($id);
            Images::deleteImage($model->imgUploadDir . $model->logo);
            $model->logo = '';
            echo $model->save();
        }
    }

}