<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-01 10:18:31
 * @version $Id$
 */

namespace leon2012\phpapi;

use leon2012\phpapi\collections\ArrayCollection;
use leon2012\phpapi\collections\HeaderCollection;

class Request  
{
    
    private $_headers;
    private $_cookie;
    private $_get;
    private $_post;
    private $_request;
    private $_server;

    private $_rawBody;
    private $_pathInfo;
    
    public function __construct()
    {
        $this->_headers = new HeaderCollection();
        $this->_cookies = new ArrayCollection($_COOKIE);
        $this->_get = new ArrayCollection($_GET);
        $this->_post = new ArrayCollection($_POST);
        $this->_request = new ArrayCollection($_REQUEST);
        $this->_server = new ArrayCollection($_SERVER);
    }

    public function get($name, $defaultValue = '')
    {
        return $this->_get->get($name, $defaultValue);
    }

    public function post($name, $defaultValue = '')
    {
        return $this->_post->get($name, $defaultValue);
    }

    public function request($name, $defaultValue = '')
    {
        return $this->_request->get($name, $defaultValue);
    }

    public function server($name, $defaultValue = '')
    {
        return $this->_server->get($name, $defaultValue);
    }

    public function getMethod()
    {
        if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
            return strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
        }
        if (isset($_SERVER['REQUEST_METHOD'])) {
            return strtoupper($_SERVER['REQUEST_METHOD']);
        }
        return 'GET';
    }
    
    public function isPost()
    {
        return $this->getMethod() == 'POST';
    }

    public function isGet()
    {
        return $this->getMethod() == 'GET';
    }

    public function isOptions()
    {
        return $this->getMethod() == 'OPTIONS';
    }

    public function isHead()
    {
        return $this->getMethod() == 'HEAD';
    }

    public function isDelete()
    {
        return $this->getMethod() == 'DELETE';
    }

    public function isPut()
    {
        return $this->getMethod() == 'PUT';
    }

    public function isPatch()
    {
        return $this->getMethod() == 'PATCH';
    }

    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    public function isAjaxp()
    {
        return $this->isAjax() && !empty($_SERVER['HTTP_X_PJAX']);
    }

    public function isFlash()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) &&
            (stripos($_SERVER['HTTP_USER_AGENT'], 'Shockwave') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'Flash') !== false);

    }

    public function getRawBody()
    {
        if ($this->_rawBody == null) {
            $this->_rawBody = file_get_contents('php://input');
        }
        return $this->_rawBody;
    }

    public function getPathInfo()
    {
        return $this->_pathInfo;
    }

    public function setPathInfo($pathInfo)
    {
        $this->_pathInfo = $pathInfo;
        return $this;
    }

    public function getQueryString()
    {
        return isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
    }

    public function getServerName()
    {
        return isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : null;
    }

    public function getServerPort()
    {
        return isset($_SERVER['SERVER_PORT']) ? (int) $_SERVER['SERVER_PORT'] : null;
    }

    public function getReferrer()
    {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
    }

    public function getUserAgent()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
    }

    public function getUserHost()
    {
        return isset($_SERVER['REMOTE_HOST']) ? $_SERVER['REMOTE_HOST'] : null;
    }



}