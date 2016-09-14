# php-api
### 调用方法

```
define('APP_PATH',realpath(dirname(__FILE__)));
require_once __DIR__."/../vendor/autoload.php";
use leon2012\phpapi\Application;
$config = [
    'id' => 'api',
    'appPath' => APP_PATH,
    'controllerNamespace' => 'api\controllers',
    'defaultRoute' => 'site/index',
    'modules' => [
        'v1' => 'api\modules\v1\Module',
    ],
    'outputFormat' => 'json',

];
$app = Application::getInstance();
try{
    $app->logger = new FileLogger('/tmp/out.log');
    $app->setConfig($config);
    $app->run();
}catch(Exception $e) {
    $app->response->setRet($e->getCode());
    $app->response->setMsg($e->getMessage());
    $app->response->setData(null);
}
$app->response->enableCache(true);
$app->response->output();