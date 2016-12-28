<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-19 11:51:05
 * @version $Id$
 */
namespace leon2012\phpapi\cache;

use leon2012\phpapi\cache\drivers;

class CacheFactory 
{
    private static $_factoryCache = [];

    public static function getCache($driverName, $params = [])
    {
       if (isset(self::$_factoryCache[$driverName])) {
            return self::$_factoryCache[$driverName];
       } else {
            $driver = null;
            switch ($driverName) {
                case 'apc':
                    $driver = new drivers\Apc();
                break;
                case 'redis':
                    $driver = new drivers\Redis($params);
                break;
                case 'memcache':
                    $driver = new drivers\Memcache($params);
                break;
                case 'yac':
                    $driver = new drivers\Yac($params);
                break;
            }
            self::$_factoryCache[$driverName] = $driver;
            return $driver;
       }
    }
}