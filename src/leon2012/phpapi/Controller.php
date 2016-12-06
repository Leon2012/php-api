<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */

namespace leon2012\phpapi;
use leon2012\phpapi\NotFoundMethodException;

abstract class Controller 
{
    
    protected     $_app;
    protected     $_class;
    private       $_id;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        
    }

    /**
     *
     */
    private function __clone()
    {

    }

    /**
     * @param $method
     * @param $args
     */
    public function __call($method, $args)
    {
        if (!method_exists($this, $method)) {
            throw new NotFoundMethodException('method: ' . $method);
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        $func = 'get'.ucfirst($name);
        if (method_exists($this, $func)) {
            return $this->{$func}();
        }
    }

    /**
     * @param $name
     * @param string $defaultValue
     * @return mixed
     */
    public function get($name, $defaultValue = '')
    {
        return $this->getApplication()->request->get($name, $defaultValue);
    }

    /**
     * @param $name
     * @param string $defaultValue
     * @return mixed
     */
    public function post($name, $defaultValue = '')
    {
        return $this->getApplication()->request->post($name, $defaultValue);
    }

    /**
     * @param $name
     * @param string $defaultValue
     * @return mixed
     */
    public function request($name, $defaultValue = '')
    {
        return $this->getApplication()->request->request($name, $defaultValue);
    }

    /**
     * @param $app
     * @return $this
     */
    public function setApplication($app)
    {
        $this->_app = $app;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getApplication()
    {
        return $this->_app;
    }

    /**
     * @param $name
     */
    public function setController($name)
    {
        $this->_class = $name;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return isset($this->_class) ? $this->_class : get_called_class();
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->_app->actionName;
    }

    /**
     * @return mixed
     */
    public function getModule()
    {
        return $this->_app->moduleClass;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    /**
     *
     */
    protected function goBack()
    {
        $uri = $_SERVER["HTTP_REFERER"];
        $this->redirect($uri);
    }

    /**
     *
     */
    protected function goHome()
    {
        $uri = $this->_app->getConfig('defaultRoute');
        $this->redirect($uri);
    }

    /**
     *
     */
    public function beforeAction(){}

    /**
     *
     */
    public function afterAction(){}

    /**
     * @param string $uri
     * @param array $params
     * @param string $type
     * @param int $httpResponseCode
     */
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