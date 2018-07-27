<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

use Phalcon\Logger;

return new \Phalcon\Config([
    'version' => '1.0',

    'database' => [
        'adapter'  => 'Mysql',
        'host'     => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname'   => 'phalcon_blog',
        'charset'  => 'utf8',

        //   "host"     => "sago3.mysql.tools",
        // "username" => "sago3_cms",
        // "password" => "mn7peyc7",
        // "dbname"   => "sago3_cms"
    ],

    'application' => [
        'appDir'            => APP_PATH . '/',
        'modelsDir'         => APP_PATH . '/common/models/',
        'migrationsDir'     => APP_PATH . '/migrations/',
        'cacheDir'          => BASE_PATH . '/cache/',

        'backendAssetsDir'  => BASE_PATH . '/public/assets/admin/',
        'backendAssets'     => '/assets/admin/',
        'frontendAssetsDir' => BASE_PATH . '/public/assets/',
        'frontendAssets'    => '/assets/',

        // This allows the baseUri to be understand project paths that are not in the root directory
        // of the webpspace.  This will break if the public/index.php entry point is moved or
        // possibly if the web server rewrite rules are changed. This can also be set to a static path.
        'baseUri'        => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]),
        'cryptSalt'      => 'eEAfR|_&G&f,+vU]:jFr!!A&+71w1Ms9~8_4L!<@[N@DyaIP_2My|:+.u>/6m,$D',
        'publicUrl'      => 'sago.in.ua',
    ],

    'logger' => [
        'path'     => BASE_PATH . '/logs/',
        'format'   => '%date% [%type%] %message%',
        'date'     => 'D j H:i:s',
        'logLevel' => Logger::DEBUG,
        'filename' => 'application.log',
    ],

    'mail' => [
        'fromName' => 'Vokuro',
        'fromEmail' => 'phosphorum@phalconphp.com',
        'smtp' => [
            'server' => 'smtp.gmail.com',
            'port' => 587,
            'security' => 'tls',
            'username' => '',
            'password' => ''
        ]
    ],
    /**
     * if true, then we print a new line at the end of each CLI execution
     *
     * If we dont print a new line,
     * then the next command prompt will be placed directly on the left of the output
     * and it is less readable.
     *
     * You can disable this behaviour if the output of your application needs to don't have a new line at end
     */
    'printNewLine' => true,
    // Set to false to disable sending emails (for use in test environment)
    'useMail' => true
]);
