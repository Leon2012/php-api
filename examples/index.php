<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */
// echo "<pre>";
// print_r($_SERVER);
// echo "</pre>";
// exit;

define('APP_PATH',realpath(dirname(__FILE__)));

require_once __DIR__."/../vendor/autoload.php";

use leon2012\phpapi\Application;
use leon2012\phpapi\exceptions\BadRequestException;
use leon2012\phpapi\logs\FileLogger;


$config = [
    'id' => 'api',
    'appPath' => APP_PATH,
    'controllerNamespace' => 'api\controllers',
    'defaultRoute' => 'site/index',
    'modules' => [
        'v1' => 'api\modules\v1\Module',
    ],
    'outputFormat' => 'json',
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
$app = Application::getInstance();
try{
    $app->logger = new FileLogger('/tmp/out.log');
    $app->setConfig($config);
    $app->run();
    // echo "<pre>";
    // print_r($app);
    // echo "</pre>";
    //$app->response->output();
}catch(Exception $e) {
    // echo "<pre>";
    // print_r($e);
    // echo "</pre>";
    $app->response->setRet($e->getCode());
    $app->response->setMsg($e->getMessage());
    $app->response->setData(null);
    
}
$app->response->enableCache(true);
$app->response->output();