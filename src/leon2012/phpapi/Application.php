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
use leon2012\phpapi\exceptions\NotFoundMethodException;
use leon2012\phpapi\exceptions\ExecuteException;
use leon2012\phpapi\collections\ConfigCollection;
use leon2012\phpapi\Config;
use leon2012\phpapi\Reflection;
use leon2012\phpapi\Controller;
use leon2012\phpapi\LoggerInterface;
use leon2012\phpapi\ErrorHandler;


use ReflectionClass;
use ReflectionException;

class Application 
{

    private static $_instance = null;
    private $_appPath;
    private $_data;
    private $_config;
    private $_loader;
    private $_modules;
    private $_errorHandler;
    public $request;
    public $response;
    public $moduleName;
    public $moduleClass;
    public $methodName;
    public $actionName;
    public $controllerName;
    public $controllerClass;
    public $controller;
    public $logger;
    

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

        if (($this->logger != null) && ($this->logger instanceof LoggerInterface)) {
            $this->_errorHandler = new ErrorHandler($this->logger);
            $this->_errorHandler->registerExceptionHandler();
            $this->_errorHandler->registerErrorHandler();
        }

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

        $this->request->setPathInfo($pathInfo);

        if (empty($pathInfo)) {
            $urlArr = explode('/', $this->getConfig('defaultRoute'));
        }else{
            $urlArr = explode('/', $pathInfo);
        }
        $moduleName = '';
        $controllerName = '';
        $methodName = '';
        $controllerClass = '';
        $params = [];
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
            if (count($urlArr) > 3) {
                $params = array_slice($urlArr, 3);
            }
        }else{
            $controllerName = $urlArr[0];
            $methodName = !empty($urlArr[1])?$urlArr[1]:'index';
            if (count($urlArr) > 2) {
                $params = array_slice($urlArr, 2);
            }
        }
        
        if (empty($moduleName)) {
            $moduleClass = null;
            $controllerClass = $this->getConfig('controllerNamespace').'\\'.$controllerName.'Controller';
        }else{
            $moduleClass = $this->_modules[$moduleName];
            $controllerClass = $moduleClass->controllerNamespace.'\\'.$controllerName.'Controller';
        }
        if (!class_exists($controllerClass)) {
            throw new NotFoundControllerException('controller: ' . $controllerClass);
        }
        $this->moduleClass = $moduleClass;
        $this->moduleName = $moduleName;
        $this->controllerName = $controllerName;
        $this->controllerClass = $controllerClass;
        $this->methodName = $methodName;
        $this->actionName = $methodName.'Action';

        $this->controller = new $controllerClass();
        $this->controller->setApplication($this);
        $this->controller->setController($this->controllerClass);

        $reflection = new Reflection($this->controller);
        $parentControllerName = '\\leon2012\\phpapi\\Controller';
        if (!$reflection->isSubclassOf($parentControllerName)) {
            throw new NotFoundControllerException(sprintf('controller: %s not instance %s', $controllerClass, $parentControllerName));
        }

        $ok = $reflection->hasMethod($this->actionName);
        if (!$ok) {
            throw new NotFoundMethodException(sprintf('controller: %s, method: %s ', $controllerClass, $this->actionName));
        }

        try{
            $this->controller->beforeAction();
            if (count($params) > 0){
                $args = $this->convParams($params);
            }else{
                $args = [];
            }
            $data = $reflection->execute($this->controller, $this->actionName, $args);
            $this->response->setData($data);

            $this->controller->afterAction();

        }catch(ReflectionException $e) {
            throw new ExecuteException(sprintf('controller: %s, method: %s ', $controllerClass, $this->actionName));
        }
    }

    public function set($key, $value)
    {
        $vars = get_object_vars($this);
        if (isset($vars[$key])) {
            return null;
        }
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
    // private function call($resource, $args = [])
    // {
    //     list($name, $method) = explode("@", $resource);
    //     $method = $method.'Action';
    //     $class = array_map('ucfirst', explode(".", $name));
    //     $className = end($class).'Controller';
    //     $namespace = str_replace(end($class), '', $class);
    //     $class = '\\'.$this->getConfig('appNamespace').'\\'.implode('\\', $namespace).$className;
    //     return call_user_func_array(new $class(), $method, $args);
    // }

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

    private function convParams($params)
    {
        $newParams = [];
        $str = implode("/", $params);
        // echo $str;
        // $newStr = '';
        // preg_replace_callback('~([a-z]+)/([a-z]+)~', function($matches) {
        //     global $newStr
        //     //print_r($matches);
        //     $newStr .= ($matches[1].'='.$matches[2].'&');
        // }, $str);
        // echo $newStr;
        preg_match_all('~([a-z]+)/([a-z]+)~', $str, $matches, PREG_SET_ORDER);
        for($i=0; $i<count($matches); $i++) {
            $match = $matches[$i];
            $newParams[$match[1]] = $match[2];
        }
        return $newParams;
    }
}