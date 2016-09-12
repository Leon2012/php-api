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
    
    protected   $_app;
    private     $_class;

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

    protected function redirect($uri = '', $params = [], $type = 'location', $httpResponseCode = 302)
    {
        if (empty($params)) {
            $url = $type.': '.$uri;
        }else{
            $url = $type.': '.$uri . '?'.http_build_query($params);
        }
        header($uri, true, $httpResponseCode);
        return $this;
    }


}