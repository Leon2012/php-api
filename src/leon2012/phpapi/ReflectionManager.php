<?php
/**
 *
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-10 10:28:43
 * @version $Id$
 */
namespace leon2012\phpapi;
use leon2012\phpapi\exceptions\CoreException;

class ReflectionManager
{
    private $_cache;
    private static $_instance = null;

    public function __construct()
    {
        $this->_cache = [];
    }

    public static function shareManager()
    {
        if (self::$_instance == null) {
            $instance = new ReflectionManager();
            self::$_instance = $instance;
        }

        return self::$_instance;
    }

    public function getReflection($obj)
    {
        if (!is_object($obj)) {
            throw new CoreException("Invalid Object");
        }
        $className = get_class($obj);
        if (isset($this->_cache[$className])) {
            return $this->_cache[$className];
        } else {
            $reflection = new Reflection($obj);
            $this->_cache[$className] = $reflection;

            return $reflection;
        }
    }

    public function getAllReflections()
    {
        return $this->_cache;
    }
}
