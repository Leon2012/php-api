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
    private $_errorHandler;
    //private $_controllerReflectionCache;
    public $request;
    public $response;
    public $uri;
    public $router;

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
        $this->setCharset();
        $appPath = $this->getConfig('appPath');
        $this->setAppPath($appPath);
        $this->initLogger();
        $this->uri = new URI(URI::PROTOCOL_REQUEST_URI);
        
        $urlArr = $this->uri->getSegments();
        if (empty($urlArr)) {
            $urlArr = explode('/', $this->getConfig('defaultRoute'));
        }
        $this->router = new Router($urlArr);
        if (!class_exists($this->router->controllerClass)) {
            throw new NotFoundControllerException('controller: ' . $this->router->controllerClass);
        }
        $this->request = new Request();
        $this->request->setPathInfo($this->uri->getUrl());
        $this->response = Response::create($this->getConfig('outputFormat'));
        $this->initDatabase();

        $this->controller = new $this->router->controllerClass();
        $this->controller->setApplication($this);
        $this->controller->setController($this->router->controllerClass);
        $this->controller->setId($this->router->controllerName);
        $parentControllerName = '\\leon2012\\phpapi\\Controller';
        if (!is_subclass_of($this->controller, $parentControllerName)) {
            throw new NotFoundControllerException(sprintf('controller: %s not instance %s', $this->router->controllerClass, $parentControllerName));
        }
        $reflection = ReflectionManager::shareManager()->getReflection($this->controller);
        $ok = $reflection->hasMethod($this->router->actionName);
        if (!$ok) {
            throw new NotFoundMethodException(sprintf('controller: %s, method: %s ', $this->router->controllerClass, $this->router->actionName));
        }
        try {
            $this->controller->beforeAction();
            $data = $reflection->execute($this->router->actionName, $this->router->params);
            $this->response->setData($data);
            $this->controller->afterAction();
        } catch (ReflectionException $e) {
            throw new ExecuteException(sprintf('controller: %s, method: %s ', $this->router->controllerClass, $this->router->actionName));
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

    private function initDatabase()
    {
        $dbConfig = $this->getConfig('database');
        if ($dbConfig) {
            $this->database = new Database($dbConfig);
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

    private function setCharset()
    {
        $charset = strtoupper($this->getConfig('charset'));
        ini_set('default_charset', $charset);
        if (extension_loaded('mbstring')){
            @ini_set('mbstring.internal_encoding', $charset);
            mb_substitute_character('none');
        }
        if (extension_loaded('iconv')){
            @ini_set('iconv.internal_encoding', $charset);
        }
        ini_set('php.internal_encoding', $charset);
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
