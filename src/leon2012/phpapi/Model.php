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
    protected static $_globalDatabase = null; //global database

    public function __construct($database = null)
    {
        parent::__construct($database);
    }

    public function database()
    {
        if ($this->_database != null) {
            return $this->_database;
        } else {
            return Model::getGlobalDatabase();
        }
    }

    public static function setGlobalDatabase($db)
    {
        self::$_globalDatabase = $db;
    }

    public static function getGlobalDatabase()
    {
        return self::$_globalDatabase;
    }
}
