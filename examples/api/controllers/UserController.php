<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-15 11:33:37
 * @version $Id$
 */
namespace api\controllers;
use api\models\UserModel;

class UserController extends \leon2012\phpapi\Controller 
{
    
   public function getAction($id)
   {
        if ($id > 0) {
            $model = new UserModel();
            $model->one($id);
            return ['id' => $id, 'username' => $model->username];
        }
   }
}