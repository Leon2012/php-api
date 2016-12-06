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
    public static $database; //global database

    public function __construct()
    {
        parent::__construct();
    }

    public function database()
    {
        return self::$database;
    }

    public static function setGlobalDatabase($db)
    {
        self::$database = $db;
    }
}