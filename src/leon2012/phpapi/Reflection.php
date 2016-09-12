<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-12 16:30:54
 * @version $Id$
 */

namespace leon2012\phpapi;

use ReflectionClass;
use ReflectionProperty;

class Reflection
{
    private $_reflectionClass;

    public function __construct()
    {
        $this->_reflectionClass = null;
    }

    public function setClass($class)
    {
        if (is_object($class)) {
            $class = get_class($class);
        }
        $this->_reflectionClass = new ReflectionClass($class);
    }

    public function getReflectionClass()
    {
        return $this->_reflectionClass;
    }

    public function hasMethod($methodName)
    {
        return $this->_reflectionClass->hasMethod($methodName);
    }

    public function getMethodParams($methodName)
    {
        $params = [];
        if (!$this->hasMethod($methodName)) {
            return $params;
        }
        $method = $this->_reflectionClass->getMethod($methodName);
        if ($method) {
            $params = $method->getParameters();
        }
        return $params;
    }
}