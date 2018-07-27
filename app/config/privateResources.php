<?php

use Phalcon\Config;
use Phalcon\Logger;

return new Config([

    'privateResources' => [

        'Users' => [
            'index' => [
                'index',
            ],
            'users' => [],
            'companies' => [],
        ],
        
        'Administrators' => [
            'users' => [
                'index',
                'search',
                'edit',
                'create',
                'delete',
                'view',
                'changePassword',
            ],
            'index' => [
                'index',
            ],
            'companies' => [
                'index',
                'search',
                'edit',
                'create',
                'delete',
                'view',
                'removeimage',
                'removefile',
            ],

        ],

        'Guests' => [

        ],
    ]
    
]);
