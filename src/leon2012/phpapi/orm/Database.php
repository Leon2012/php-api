<?php
/**
 *
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */

namespace leon2012\phpapi\orm;

use leon2012\phpapi\orm\exceptions\InvalidConfigException;
use leon2012\phpapi\exceptions\NotFoundMethodException;

class Database
{

    private $_config;
    private $_driver;
    private $_supportDrivers = ['pdo', 'mysqli', 'mysql'];
    private $_supportDatabaseTypes = ['mysql'];

    public function __construct(array $config)
    {
        $this->checkConfig($config);
        $this->initDriver();
    }

    public function __destruct()
    {
        $this->_driver->close();
    }

    private function initDriver()
    {
        $this->_driver = DriverFactory::getDriver($this->getConfig('driver'));
        $this->_driver->open($this->_config);
    }

    public function getConfig($key)
    {
        return $this->_config[$key];
    }

    public function __call($name, $arguments = null)
    {
        if (!method_exists($this->_driver, $name)) {
            throw new NotFoundMethodException($name);
        }
        $args = [];
        if (!is_null($arguments)) {
            if (!is_array($arguments)) {
                $args[0] = $arguments;
            } else {
                $args = $arguments;
            }
        }

        return call_user_func_array([$this->_driver, $name], $args);
    }

    public function __debugInfo()
    {
        return [
            'driver' => get_class($this->_driver),
            'config' => $this->_config,
            'lastSql' => $this->_driver->getLastSql(),
        ];
    }

    private function checkConfig(array $config)
    {
        if (!isset($config['driver']) || empty($config['driver'])) {
            throw new InvalidConfigException('no driver config');
        }

        if (!in_array(strtolower($config['driver']), $this->_supportDrivers)) {
            throw new InvalidConfigException('no support driver');
        }

        if (!isset($config['type']) || empty($config['type'])) {
            throw new InvalidConfigException('no type config');
        }

        if (!in_array(strtolower($config['type']), $this->_supportDatabaseTypes)) {
            throw new InvalidConfigException('no support type');
        }

        if (!isset($config['host']) || empty($config['host'])) {
            throw new InvalidConfigException('no host config');
        }

        if (!isset($config['port']) || empty($config['port'])) {
            throw new InvalidConfigException('no port config');
        }

        if (!isset($config['name']) || empty($config['name'])) {
            throw new InvalidConfigException('no port config');
        }

        if (!isset($config['username']) || empty($config['username'])) {
            throw new InvalidConfigException('no user name config');
        }

        if (!isset($config['password'])) {
            throw new InvalidConfigException('no password config');
        }

        $this->_config = $config;
    }
}
