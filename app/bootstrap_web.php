<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

/**
 *
 *   Debug reporting
 *   Production off
 */
(new Phalcon\Debug)->listen();

try {

    /**
     * The FactoryDefault Dependency Injector automatically registers the services that
     * provide a full stack framework. These default services can be overidden with custom ones.
     */
    $di = new FactoryDefault();

    /**
     * Include general services
     */
    require APP_PATH . '/config/services.php';

    /**
     * Include web environment specific services
     */
    require APP_PATH . '/config/services_web.php';

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Include Autoloader
     */
    include APP_PATH . '/config/loader.php';

    /**
     * Handle the request
     */
    $application = new Application($di);
    $di['app'] = $application; //  Important

    /**
     * Register application modules
     */
    $application->registerModules([
        'frontend' => [
            'className' => 'App\Frontend\Module',
            'path'      => APP_PATH . '/modules/frontend/Module.php'
        ],
        'backend' => [
            'className' => 'App\Backend\Module',
            'path'      => APP_PATH . '/modules/backend/Module.php'
        ],
    ]);

    /**
     * Include routes
     */
    require APP_PATH . '/config/routes.php';

    // (new Snowair\Debugbar\ServiceProvider(APP_PATH . '/common/library/config/debugbar.php'))->start();

    if($config->printNewLine){
        echo $application->handle()->getContent();
    }else{
        echo str_replace(["\n","\r","\t"], '', $application->handle()->getContent());  
    }
} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
