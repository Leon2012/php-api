<?php
/**
 *
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-15 11:33:37
 * @version $Id$
 */
namespace api\controllers;

use api\models\UserModel;
use leon2012\phpapi\DynamicModel;

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

   public function mgetAction($id)
   {
        if ($id > 0) {
            $model = new DynamicModel('api_log');
            $model->one($id);
            return ['id' => $id, 'request' => $model->request];
        }
   }
}
