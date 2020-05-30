<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);

$dotenv->load();

return [

    /*
    |--------------------------------------------------------------------------
    | Server Objects
    |--------------------------------------------------------------------------
    |
    | This value should contain a path to a valid json. The json will 
    | be used in loading the server objects.
    |
    */

    'server_objects' => $_ENV['SERVER_OBJECTS'],

    /*
    |--------------------------------------------------------------------------
    | Security Key
    |--------------------------------------------------------------------------
    |
    | This value should contain a path to a valid security file. This is optional
    | 
    */

    'pem' => $_ENV['PEM'],
];