<?php
/**
 * @Author: PengYe
 * @Date:   2017-06-14 10:10:11
 * @Last Modified by:   PengYe
 * @Last Modified time: 2017-06-14 10:11:08
 */
namespace leon2012\phpapi;

class Router
{
	private $_modules;
	private $_defaultAction = 'index';
	public $moduleClass;
	public $moduleName;
	public $controllerClass;
	public $controllerName;
	public $methodName;
	public $actionName;
	public $params;

	public function __construct($urlArr)
	{
		$this->_modules = [];
		$this->params = [];
		$this->initModules();
		$this->setRouting($urlArr);
	}

	private function setRouting($urlArr)
	{
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
                $moduleDefaultMethod =	 $moduleDefaultRouteArr[1];
            } else {
                $moduleDefaultMethod = $this->_defaultAction;
            }
            $controllerName = !empty($urlArr[1])?$urlArr[1]:$moduleDefaultController;
            $methodName = !empty($urlArr[2])?$urlArr[2]:$moduleDefaultMethod;
            if (count($urlArr) > 3) {
                $params = array_slice($urlArr, 3);
            }
        } else {
            $controllerName = $urlArr[0];
            $methodName = !empty($urlArr[1])?$urlArr[1]:$this->_defaultAction;
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

        $this->moduleClass = $moduleClass;
        $this->moduleName = $moduleName;
        $this->controllerName = $controllerName;
        $this->controllerClass = $controllerClass;
        $this->methodName = $methodName;
		$this->actionName = $methodName.'Action';
		if (count($params) > 0) {
            $this->params = $this->convParams($params);
            $_GET = array_merge($_GET, $this->params);
		}
	}

	/**
     * init modules
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
	
	private function getConfig($name)
	{
		return Application::getInstance()->getConfig($name);
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
}

