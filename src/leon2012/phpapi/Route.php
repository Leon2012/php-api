<?php
/**
 *
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */

namespace leon2012\phpapi;

use leon2012\phpapi\exceptions\InvalidArgumentException;

class Route
{
    private $_url;
    private $_target;
    private $_method;
    private $_name;
    private $_httpMethods = ['GET', 'POST', 'PUT', 'DELETE'];

    public function __construct($method, $url, $target)
    {
       if (!preg_match("/^([a-z0-9]+)\:([a-z0-9]+)$/", $target)) {
            throw new InvalidArgumentException("invalid target");
       }
       if (!preg_match("/^\/([a-z0-9]+)(\/[a-z0-9{}:]+)*$/", $url)) {
            throw new InvalidArgumentException("invalid url");
       }
       $method = strtoupper($method);
       if (!in_array($method, $this->_httpMethods)) {
            throw new InvalidArgumentException('invalid http method');
       }
       $this->_url = $url;
       $this->_target = $target;
       $this->_method = $method;
    }

    public function getUrl()
    {
        return $this->_url;
    }

    public function setUrl($url)
    {
        $url = (string)$url;
        if (substr($url, -1) !== '/') {
            $url .= '/';
        }
        $this->_url = $url;
    }

    public function getTarget()
    {
        return $this->_target;
    }

    public function setTarget($target)
    {
        $this->_target = $target;
    }

    public function getMethod()
    {
        return $this->_method;
    }

    public function setMethod($method)
    {
        $this->_method = $method;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function match($urlString)
    {
        $regex = '/^'.$this->regex.'$/';

        return (bool) preg_match($regex, $str);
    }
}
