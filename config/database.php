<?php
use Illuminate\Support\Facades\Config;

return [

    /*
    |--------------------------------------------------------------------------
    | PDO Fetch Style
    |--------------------------------------------------------------------------
    |
    | By default, database results will be returned as instances of the PHP
    | stdClass object; however, you may desire to retrieve records in an
    | array format for simplicity. Here you can tweak the fetch style.
    |
    */

    'fetch' => PDO::FETCH_CLASS,

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'testing' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ],

        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => env('DB_DATABASE', base_path('database/database.sqlite')),
            'prefix'   => env('DB_PREFIX', ''),
        ],

        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST'.env('DB_BETA')),
            'port'      =>env('DB_PORT'.env('DB_BETA')),
            'database'  => env('DB_DATABASE'.env('DB_BETA')),
            'username'  => env('DB_USERNAME'.env('DB_BETA')),
            'password'  => env('DB_PASSWORD'.env('DB_BETA')),
            'charset'   =>  'utf8',
            'collation' =>  'utf8_unicode_ci',
            'prefix'    =>  '',
            'timezone'  =>  '+00:00',
            'strict'    =>  false,
        ],

        'mysql_backend' => [
            'driver'    => 'mysql',
            'host'      => env('DB_BACKEND_HOST', 'localhost'),
            'port'      => env('DB_BACKEND_PORT', 3306),
            'database'  => env('DB_BACKEND_DATABASE', 'forge'),
            'username'  => env('DB_BACKEND_USERNAME', 'forge'),
            'password'  => env('DB_BACKEND_PASSWORD', ''),
            'charset'   => env('DB_CHARSET', 'utf8'),
            'collation' => env('DB_COLLATION', 'utf8_unicode_ci'),
            'prefix'    => env('DB_PREFIX', ''),
            'timezone'  => env('DB_TIMEZONE', '+00:00'),
            'strict'    => env('DB_STRICT_MODE', false),
        ],

        'pgsql' => [
            'driver'   => 'pgsql',
            'host'     => env('DB_HOST', 'localhost'),
            'port'     => env('DB_PORT', 5432),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset'  => env('DB_CHARSET', 'utf8'),
            'prefix'   => env('DB_PREFIX', ''),
            'schema'   => env('DB_SCHEMA', 'public'),
        ],

        'sqlsrv' => [
            'driver'   => 'sqlsrv',
            'host'     => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset'  => env('DB_CHARSET', 'utf8'),
            'prefix'   => env('DB_PREFIX', ''),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [
        'client' => 'predis',
        'default' => [
            'host' => env('REDIS_HOST'.env('DB_BETA')),//169.169.61.237
            'password' => env('REDIS_PASSWORD'.env('DB_BETA')),
            'port' => env('REDIS_PORT'.env('DB_BETA')),
            'database' => 0,
            'read_write_timeout' => 60,
        ],
        'options' => [
            'cluster' => 'redis',
        ],
        'clusters' => [
            'mycluster1' => [
                [
                    'host' => env('REDIS_HOST'.env('DB_BETA')),
                    'password' => env('REDIS_PASSWORD'.env('DB_BETA')),
                    'port' => env('REDIS_PORT_FIRST'.env('DB_BETA')),
                    'database' => 0,
                    'read_write_timeout' => env('REDIS_TIMEOUT',60),
                ],
                [
                    'host' => env('REDIS_HOST'.env('DB_BETA')),
                    'password' => env('REDIS_PASSWORD'.env('DB_BETA')),
                    'port' => env('REDIS_PORT_SECOND'.env('DB_BETA')),
                    'database' => 0,
                    'read_write_timeout' => env('REDIS_TIMEOUT',60),
                ],
                [
                    'host' => env('REDIS_HOST'.env('DB_BETA')),
                    'password' => env('REDIS_PASSWORD'.env('DB_BETA')),
                    'port' => env('REDIS_PORT_THIRD'.env('DB_BETA')),
                    'database' => 0,
                    'read_write_timeout' => env('REDIS_TIMEOUT',60),
                ],
            ]
        ],
    ],

];
