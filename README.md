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
        'log' => [
            'output' => 'file',
            'level'  => 1, //info
            'file'   => '/tmp/out.log',
        ],
        'database' => [
            'driver' => 'pdo',  //support driver
            'type' => 'mysql',  //only support mysql
            'host' => '127.0.0.1',       //mysql host
            'port' => '3306',       //mysql port
            'name' => '3db',       //database name
            'username' => 'root',   //user name
            'password' => '******',   //password
            'tablePrefix' => 'cms_',
            'charset' => 'utf8', 
        ],
    ];
    $app = Application::getInstance();
    try{
        $app->setConfig($config);
        $app->run();
    }catch(Exception $e) {
        $app->response->setRet($e->getCode());
        $app->response->setMsg($e->getMessage());
        $app->response->setData(null);
    }
    $app->response->enableCache(true);
    $app->response->output();
```
