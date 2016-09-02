<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-01 10:18:09
 * @version $Id$
 */

namespace leon2012\phpapi;

use leon2012\phpapi\exceptions\CoreException;
use leon2012\phpapi\collections\ConfigCollection;

class Application 
{

    private static $_instance = null;
    private $_appPath;
    private $_data;
    private $_config;
    public $request;
    public $response;
    public $loader;


    private function __construct()
    {
        $this->_data = [];
        $this->request = new Request();
        $this->response = new Response();
        $this->loader = new Autoloader();
    }

    public static function getInstance()
    {
        if (self::$_instance == null) {
            $instance = new Application();
            self::$_instance = $instance;
        }
        return self::$_instance;
    }

    public function set($key, $value)
    {
        $this->_data[$key] = $value;
        return $this;
    }

    public function get($key, $defaultValue = null)
    {
        if (!isset($this->_data[$key])) {
            return $defaultValue;
        }
        return $this->_data[$key];
    }

    public function setAppPath($path)
    {
        $this->_appPath = $path;
        $this->loader->setBasePath($this->_appPath);
    }

    public function setConfig($config = [])
    {
        $this->_config = new ConfigCollection($config);
    }

    public function __call($name, $arguments) 
    {
        if (substr($name, 0, 3) == 'set') {
            $key = lcfirst(substr($name, 3));
            return $this->set($key, isset($arguments[0]) ? $arguments[0] : NULL);
        } else if (substr($name, 0, 3) == 'get') {
            $key = lcfirst(substr($name, 3));
            return $this->get($key, isset($arguments[0]) ? $arguments[0] : NULL);
        } else {
            $format = "Call to undefined method {%s}";
            $message = sprintf($format, $name);
            throw new CoreException($message);
        }
    }

    public function __set($name, $value) 
    {
        $this->set($name, $value);
    }

    public function __get($name) 
    {
        return $this->get($name, NULL);
    }
}