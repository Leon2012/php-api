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

    ###Model调用###
    
    class UserModel extends Model
    {
        public function tableName()
        {
            return 'cms_user';
        }

        public function pkId()
        {
            return 'id';
        }
    }
    $config = [
        'driver' => 'pdo',  //support driver
        'type' => 'mysql',  //only support mysql
        'host' => '127.0.0.1',       //mysql host
        'port' => '3306',       //mysql port
        'name' => '3db',       //database name
        'username' => 'root',   //user name
        'password' => '******',   //password
        'tablePrefix' => 'cms_',
        'charset' => 'utf8', 
    ];
    $database = new Database($config);
    var_dump($database);

    $userModel = new UserModel($database);
    var_dump($userModel);

    $userModel->username = "username";
    $userModel->auth_key = "auth_key";
    $userModel->status = 1;
    $userModel->used = false;
    $data = ['username' => 'leon', 'auth_key' => 'test', 'status' => 1, 'used' => true];
    $userModel->insert($data);

    $userModel->id = 1;
    $userModel->delete($data);
    $userModel->id = 1;
    $userModel->update($data);

    $userModel->id = 1;
    $ret = $userModel->one(2);
    if ($ret) {
        echo $userModel->username;
    }

```
