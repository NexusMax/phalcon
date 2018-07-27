<?php

$router = $di->getRouter();

foreach ($application->getModules() as $key => $module) {
    $namespace = preg_replace('/Module$/', 'Controllers', $module["className"]);

    if($key === 'backend'){
        $alias = '/admin';
    }elseif($key === 'frontend'){
        $alias = '';
    }else{
        $alias = '/'.$key;
    }
    $router->add($alias.'/:params', [
        'namespace' => $namespace,
        'module' => $key,
        'controller' => 'index',
        'action' => 'index',
        'params' => 1
    ])->setName($key);
    $router->add($alias.'/:controller/:params', [
        'namespace' => $namespace,
        'module' => $key,
        'controller' => 1,
        'action' => 'index',
        'params' => 2
    ]);
    $router->add($alias.'/:controller/:action/:params', [
        'namespace' => $namespace,
        'module' => $key,
        'controller' => 1,
        'action' => 2,
        'params' => 3
    ]);
}

$backendNamespace = 'App\Backend\Controllers';

$router->add(
    '/admin/menu/edit-item/:params',
    [
        'module'     => 'backend',
        'namespace'  => $backendNamespace,
        'controller' => 'menu',
        'action'     => 'editItem',
        'params'     => 1
    ]
);
$router->add(
    '/admin/menu/view-item/:params',
    [
        'module'     => 'backend',
        'namespace'  => $backendNamespace,
        'controller' => 'menu',
        'action'     => 'viewItem',
        'params'     => 1
    ]
);
$router->add(
    '/admin/menu/delete-item/:params',
    [
        'module'     => 'backend',
        'namespace'  => $backendNamespace,
        'controller' => 'menu',
        'action'     => 'deleteItem',
        'params'     => 1
    ]
);