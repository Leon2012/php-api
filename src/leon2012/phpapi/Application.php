<?php
/**
 *
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */
namespace leon2012\phpapi;

use leon2012\phpapi\exceptions\CoreException;
use leon2012\phpapi\exceptions\NotFoundControllerException;
use leon2012\phpapi\exceptions\NotFoundMethodException;
use leon2012\phpapi\exceptions\ExecuteException;
use leon2012\phpapi\traits\DebugTrait;
use leon2012\phpapi\orm\Database;
use leon2012\phpapi\logs\FileLogger;

use ReflectionException;

final class Application
{

    private static $_instance = null;
    private $_appPath;
    private $_data;
    private $_config;
    private $_loader;
    private $_modules;
    private $_errorHandler;
    //private $_controllerReflectionCache;
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
    public $database;

    use DebugTrait;

    /**
     * Application constructor.
     */
    private function __construct()
    {
        $this->_data = [];
        $this->_modules = [];
        $this->_loader = new Autoloader();
    }

    /**
     * @return Application|null
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            $instance = new Application();
            self::$_instance = $instance;
        }

        return self::$_instance;
    }

    /**
     * @throws ExecuteException
     * @throws NotFoundControllerException
     * @throws NotFoundMethodException
     */
    public function run()
    {
        $this->initLogger();
        $this->request = new Request();
        $this->response = Response::create($this->getConfig('outputFormat'));
        $appPath = $this->getConfig('appPath');
        $this->setAppPath($appPath);
        $this->initModules();
        $this->initDatabase();

        $requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
        $pathInfo = '';
        if (!empty($_SERVER['PATH_INFO'])) {
            $pathInfo = $_SERVER['PATH_INFO'];
        } elseif (!empty($_SERVER['ORIG_PATH_INFO']) && $_SERVER['ORIG_PATH_INFO'] !== '/index.php') {
            $pathInfo = $_SERVER['ORIG_PATH_INFO'];
        } else {
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
        } else {
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
            } else {
                $moduleDefaultMethod = 'index';
            }
            $controllerName = !empty($urlArr[1])?$urlArr[1]:$moduleDefaultController;
            $methodName = !empty($urlArr[2])?$urlArr[2]:$moduleDefaultMethod;
            if (count($urlArr) > 3) {
                $params = array_slice($urlArr, 3);
            }
        } else {
            $controllerName = $urlArr[0];
            $methodName = !empty($urlArr[1])?$urlArr[1]:'index';
            if (count($urlArr) > 2) {
                $params = array_slice($urlArr, 2);
            }
        }

        if (empty($moduleName)) {
            $moduleClass = null;
            $controllerClass = $this->getConfig('controllerNamespace').'\\'.$controllerName.'Controller';
        } else {
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
        $this->controller->setId($controllerName);
        $parentControllerName = '\\leon2012\\phpapi\\Controller';
        if (!is_subclass_of($this->controller, $parentControllerName)) {
            throw new NotFoundControllerException(sprintf('controller: %s not instance %s', $controllerClass, $parentControllerName));
        }

        $reflection = ReflectionManager::shareManager()->getReflection($this->controller);
        $ok = $reflection->hasMethod($this->actionName);
        if (!$ok) {
            throw new NotFoundMethodException(sprintf('controller: %s, method: %s ', $controllerClass, $this->actionName));
        }
        try {
            $this->controller->beforeAction();
            if (count($params) > 0) {
                $args = $this->convParams($params);
            } else {
                $args = [];
            }
            $data = $reflection->execute($this->actionName, $args);
            $this->response->setData($data);
            $this->controller->afterAction();
        } catch (ReflectionException $e) {
            throw new ExecuteException(sprintf('controller: %s, method: %s ', $controllerClass, $this->actionName));
        }
    }

    /**
     * @param $key
     * @param $value
     * @return $this|null
     */
    public function set($key, $value)
    {
        $vars = get_object_vars($this);
        if (isset($vars[$key])) {
            return null;
        }
        $this->_data[$key] = $value;

        return $this;
    }

    /**
     * @param $key
     * @param  null       $defaultValue
     * @return mixed|null
     */
    public function get($key, $defaultValue = null)
    {
        if (!isset($this->_data[$key])) {
            return $defaultValue;
        }

        return $this->_data[$key];
    }

    /**
     * @param $path
     */
    public function setAppPath($path)
    {
        $this->_appPath = $path;
        $this->_loader->setBasePath($this->_appPath);
    }

    /**
     * @param array $config
     */
    public function setConfig($config = [])
    {
        $this->_config = new Config($config);
        $this->_config->valid();
    }

    /**
     * @param  string $name
     * @return mixed
     */
    public function getConfig($name = '')
    {
        if (empty($name)) {
            return $this->_config->toArray();
        } else {
            return $this->_config->$name;
        }
    }

    /**
     * @param $name
     * @param $arguments
     * @return Application|mixed|null
     * @throws CoreException
     */
    public function __call($name, $arguments)
    {
        if (substr($name, 0, 3) == 'set') {
            $key = lcfirst(substr($name, 3));

            return $this->set($key, isset($arguments[0]) ? $arguments[0] : NULL);
        } elseif (substr($name, 0, 3) == 'get') {
            $key = lcfirst(substr($name, 3));

            return $this->get($key, isset($arguments[0]) ? $arguments[0] : NULL);
        } else {
            $format = "Call to undefined method {%s}";
            $message = sprintf($format, $name);
            throw new CoreException($message);
        }
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return $this->get($name, NULL);
    }

    /**
     *
     */
    private function __clone()
    {
        return null;
    }

    /**
     *
     */
    private function initModules()
    {
        $modules = $this->getConfig('modules');
        if (is_array($modules)) {
            foreach ($modules as $name => $class) {
                $classFile = '\\'.$class;
                $obj = new $classFile();
                if ($obj) {
                    $this->_modules[$name] = $obj;
                }
            }
        }
    }

    private function initDatabase()
    {
        $dbConfig = $this->getConfig('database');
        if ($dbConfig) {
            $this->database = new Database($dbConfig);
            Model::setGlobalDatabase($this->database);
        }
    }

    private function initLogger()
    {
        $logConfig = $this->getConfig('log');
        if ($logConfig) {
            $output = $logConfig['output'];
            if ("file" == $output) {
                $this->logger = new FileLogger($logConfig['file']);
                $this->logger->setOutputLevel($logConfig['level']);
            }
        }
        if (($this->logger != null) && ($this->logger instanceof LoggerInterface)) {
            $this->_errorHandler = new ErrorHandler($this->logger);
            $this->_errorHandler->registerExceptionHandler();
            $this->_errorHandler->registerErrorHandler();
        }

    }

    /**
     * @param $params
     * @return array
     */
    private function convParams($params)
    {
        $newParams = [];
        $str = implode("/", $params);
        preg_match_all('~([a-zA-z0-9]+)/([a-zA-z0-9]+)~', $str, $matches, PREG_SET_ORDER);
        for ($i=0; $i<count($matches); $i++) {
            $match = $matches[$i];
            $newParams[$match[1]] = $match[2];
        }

        return $newParams;
    }

    // private function getControllerReflection($controller)
    // {
    //     $className = $controller->getController();
    //     if (isset($this->_controllerReflectionCache[$className])) {
    //         return $this->_controllerReflectionCache[$className];
    //     } else {
    //         $reflection = new Reflection($controller);
    //         $this->_controllerReflectionCache[$className] = $reflection;
    //         return $reflection;
    //     }
    // }
}
