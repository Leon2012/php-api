<?php
require_once __DIR__."/../vendor/autoload.php";
use leon2012\phpapi\orm\Model;
use leon2012\phpapi\orm\Database;

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
//var_dump($database);

$userModel = new UserModel($database);
//var_dump($userModel);

// $userModel->username = "username";
// $userModel->auth_key = "auth_key";
// $userModel->status = 1;
// $userModel->used = false;
// $data = ['username' => 'leon', 'auth_key' => 'test', 'status' => 1, 'used' => true];
//$userModel->insert($data);

//$userModel->id = 1;
//$userModel->delete($data);
//$userModel->id = 1;
//$userModel->update($data);

$userModel->id = 1;
$ret = $userModel->one(2);
if ($ret) {
    echo $userModel->username;
}
