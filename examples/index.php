<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-05 11:05:26
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

$config = [
    'id' => 'api',
    'appPath' => APP_PATH,
    'controllerNamespace' => 'api\controllers',
    'defaultRoute' => 'site/index',
    'modules' => [
        'v1' => 'api\modules\v1\Module',
    ],
    'outputFormat' => 'xml',

];
$app = Application::getInstance();
try{
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
$app->response->output();