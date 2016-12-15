<?php
/**
 *
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-06 10:06:44
 * @version $Id$
 */
namespace leon2012\phpapi\orm;
use leon2012\phpapi\orm\drivers\PDODriver;

class DriverFactory
{
    private static $_factoryCache = [];

    public static function getDriver($driverName)
    {
       if (isset(self::$_factoryCache[$driverName])) {
            return self::$_factoryCache[$driverName];
       } else {
            $driver = null;
            switch ($driverName) {
                case 'pdo':
                    $driver = new PDODriver();
                break;

                case 'mysqli':

                break;

                case 'mysql':

                break;
            }
            self::$_factoryCache[$driverName] = $driver;

            return $driver;
       }
    }
}
