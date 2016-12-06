<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */

namespace leon2012\phpapi\orm;
use leon2012\phpapi\orm\exceptions\InvalidConfigException;

class Database 
{

	private $_config;
	private $_driver;
    private $_supportDrivers = ['pdo', 'mysqli'];
    private $_supportTypes = ['mysql'];

	public function __construct(array $config)
	{
        $this->checkConfig($config);
         
	}

    private function initDriver()
    {
        
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

        if (!in_array(strtolower($config['type']), $this->_supportTypes)) {
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
