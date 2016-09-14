<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-01 10:18:09
 * @version $Id$
 */

namespace leon2012\phpapi;

use leon2012\phpapi\exceptions\CoreException;
use leon2012\phpapi\exceptions\NotFoundControllerException;
use leon2012\phpapi\collections\ConfigCollection;
use leon2012\phpapi\Config;


class Application 
{

    private static $_instance = null;
    private $_appPath;
    private $_data;
    private $_config;
    private $_loader;
    private $_modules;
    public $request;
    public $response;
    public $moduleName;
    public $controllerName;
    public $methodName;
    public $controllerClass;
    

    private function __construct()
    {
        $this->_data = [];
        $this->_modules = [];
        $this->_loader = new Autoloader();
    }

    public static function getInstance()
    {
        if (self::$_instance == null) {
            $instance = new Application();
            self::$_instance = $instance;
        }
        return self::$_instance;
    }

    public function run()
    {
        $appPath = $this->getConfig('appPath');
        $this->setAppPath($appPath);
        $this->initModules();

        $this->request = new Request();
        $this->response = Response::create($this->getConfig('outputFormat'));

        $requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
        $pathInfo = '';
        if (!empty($_SERVER['PATH_INFO'])) {
            $pathInfo = $_SERVER['PATH_INFO'];
        }elseif (!empty($_SERVER['ORIG_PATH_INFO']) && $_SERVER['ORIG_PATH_INFO'] !== '/index.php') {
            $pathInfo = $_SERVER['ORIG_PATH_INFO'];
        }else{
            if (!empty($_SERVER['REQUEST_URI'])) {
                $pathInfo = (strpos($_SERVER['REQUEST_URI'], '?') > 0)? strstr($_SERVER['REQUEST_URI'], '?', true) : $_SERVER['REQUEST_URI'];
            }
        }
        if ($pathInfo[0] == '/') {
            $pathInfo = substr($pathInfo, 1);
        }
        $urlArr = explode('/', $pathInfo);
        $moduleName = '';
        $controllerName = '';
        $methodName = '';
        $controller = '';
        if (empty($urlArr)) {
            $urlArr = explode('/', $this->getConfig('defaultRoute'));
        }
        if (isset($this->_modules[$urlArr[0]])) {//module.controller.method
            $moduleName = $urlArr[0];
            $moduleClass = $this->_modules[$moduleName];
            $moduleDefaultRoute = $moduleClass->defaultRoute;
            $moduleDefaultRouteArr = explode('/', $moduleDefaultRoute);
            $moduleDefaultController = $moduleDefaultRouteArr[0];
            if (count($moduleDefaultRouteArr) > 1) {
                $moduleDefaultMethod = $moduleDefaultRouteArr[1];
            }else{
                $moduleDefaultMethod = 'index';
            }
            $controllerName = !empty($urlArr[1])?$urlArr[1]:$moduleDefaultController;
            $methodName = !empty($urlArr[2])?$urlArr[2]:$moduleDefaultMethod;
        }else{
            $controllerName = $urlArr[0];
            $methodName = !empty($urlArr[1])?$urlArr[1]:'index';
        }
        if (empty($moduleName)) {
            $controller = $this->getConfig('controllerNamespace').'\\'.$controllerName.'Controller';
        }else{
            $moduleClass = $this->_modules[$moduleName];
            $controller = $moduleClass->controllerNamespace.'\\'.$controllerName.'Controller';
        }
        if (!class_exists($controller)) {
            throw new NotFoundControllerException('controller ' . $controller);
        }

        $this->moduleName = $moduleName;
        $this->controllerName = $controllerName;
        $this->controllerClass = $controller;
        $this->methodName = $methodName;

        //echo $moduleName.'.'.$controllerName.'.'.$methodName;


        
        //throw new NotFoundControllerException("no controller found");
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
        $this->_loader->setBasePath($this->_appPath);
    }

    public function setConfig($config = [])
    {
        $this->_config = new Config($config);
        $this->_config->valid();
    }

    public function getConfig($name = '')
    {
        if (empty($name)) {
            return $this->_config->toArray();
        }else{
            return $this->_config->$name;
        }
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

    private function __clone()
    {

    }

    /**
     * <code>
     * // Call the "user/admin" controller and pass parameters
     *   $response = $this->call('modules.admin.user@profile', $arguments);
     * </code>
     */
    private function call($resource, $args = [])
    {
        list($name, $method) = explode("@", $resource);
        $method = $method.'Action';
        $class = array_map('ucfirst', explode(".", $name));
        $className = end($class).'Controller';
        $namespace = str_replace(end($class), '', $class);
        $class = '\\'.$this->getConfig('appNamespace').'\\'.implode('\\', $namespace).$className;
        return call_user_func_array(new $class(), $method, $args);
    }

    private function initModules()
    {
        $modules = $this->getConfig('modules');
        if (is_array($modules)) {
            foreach($modules as $name => $class) {
                $classFile = '\\'.$class;
                $obj = new $classFile();
                if ($obj) {
                    $this->_modules[$name] = $obj;
                }
            }
        }
    }
}