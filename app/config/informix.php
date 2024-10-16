<?php

return [
    'informix' => [
        'driver'          => 'informix',
        'host'            => env('DB_HOST', 'informix'),
        'database'        => env('DB_DATABASE', 'laraprimevue'),
        'username'        => env('DB_USERNAME', 'informix'),
        'password'        => env('DB_PASSWORD', 'in4mix'),
        'service'         => env('DB_SERVICE', '9088'),
        'server'          => env('DB_SERVER', 'informix'),
        'db_locale'       => 'en_US.819',
        'client_locale'   => 'en_US.819',
        'db_encoding'     => 'GBK',
        'initSqls'        => false,
        'client_encoding' => 'UTF-8',
        'prefix'          => '',
    ],
];
