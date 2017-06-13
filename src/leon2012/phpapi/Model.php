<?php
/**
 *
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */
namespace leon2012\phpapi;

use leon2012\phpapi\orm\Model as BaseModel;

class Model extends BaseModel
{
    public function __construct($database = null)
    {
        parent::__construct($database);
    }

    public function database()
    {
        if ($this->_database != null) {
            return $this->_database;
        } else {
            return Application::getInstance()->database;
        }
    }
}
