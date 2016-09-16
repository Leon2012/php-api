<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-02 16:48:28
 * @version $Id$
 */

namespace leon2012\phpapi;
use leon2012\phpapi\NotFoundMethodException;

abstract class Controller 
{
    
    protected       $_app;
    protected       $_class;

    public function __construct()
    {
        
    }

    private function __clone()
    {

    }

    public function __call($method, $args)
    {
        if (!method_exists($this, $method)) {
            throw new NotFoundMethodException('method: ' . $method);
        }
    }
    
    public function __get($name)
    {
        $func = 'get'.ucfirst($name);
        if (method_exists($this, $func)) {
            return $this->{$func}();
        }
    }

    public function get($name, $defaultValue = '')
    {
        return $this->getApplication()->request->get($name, $defaultValue);
    }

    public function post($name, $defaultValue = '')
    {
        return $this->getApplication()->request->post($name, $defaultValue);
    }

    public function request($name, $defaultValue = '')
    {
        return $this->getApplication()->request->request($name, $defaultValue);
    }

    public function setApplication($app)
    {
        $this->_app = $app;
        return $this;
    }

    public function getApplication()
    {
        return $this->_app;
    }

    public function setController($name)
    {
        $this->_class = $name;
    }

    public function getController()
    {
        return isset($this->_class) ? $this->_class : get_called_class();
    }

    public function getAction()
    {
        return $this->_app->actionName;
    }

    public function getModule()
    {
        return $this->_app->moduleClass;
    }

    protected function goBack()
    {
        $uri = $_SERVER["HTTP_REFERER"];
        $this->redirect($uri);
    }

    protected function goHome()
    {
        $uri = $this->_app->getConfig('defaultRoute');
        $this->redirect($uri);
    }

    public function beforeAction(){}

    public function afterAction(){}

    protected function redirect($uri = '', $params = [], $type = 'location', $httpResponseCode = 302)
    {
        if (strpos($uri, 'http') != false) {
            $url = "//{$_SERVER['HTTP_HOST']}/{$uri}";
        }else{
            $url = $uri;
        }
        if (empty($params)) {
            $url = $type.': '.$url;
        }else{
            $url = $type.': '.$url . '?'.http_build_query($params);
        }
        header($url, true, $httpResponseCode);
        exit;
    }


}