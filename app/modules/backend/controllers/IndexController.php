<?php

namespace App\Backend\Controllers;

class IndexController extends ControllerBase
{	
	public function initialize()
    {
        parent::initialize();
        $this->view->setTemplateAfter('common');
    }

    public function indexAction()
    {
        
    }

    public function twoAction()
    {
         return $this->indexAction();
         exit(0);
    }
}

