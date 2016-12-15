<?php
require_once './UserModel.php';
$config = [
    'database' => [
        'driver' => 'pdo',  //support driver
        'type' => 'mysql',  //only support mysql
        'host' => '',       //mysql host
        'port' => '',       //mysql port
        'name' => '',       //database name
        'username' => '',   //user name
        'password' => '',   //password
        'tablePrefix' => 'cms_',
        'charset' => 'utf8',
    ],
];
