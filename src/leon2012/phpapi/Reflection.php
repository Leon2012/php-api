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

    public function __construct($class)
    {
       $this->setClass($class);
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

    public function getParentClassNames()
    {
        $parents = array();
        while ($parent = $this->_reflectionClass->getParentClass()) {
            $parents[] = $parent->getName();
            $class = $parent;
        }
        return $parents;
    }

    public function isInstance($object)
    {
        return $this->_reflectionClass->isInstance($object);
    }

    public function isSubclassOf($class)
    {
        return $this->_reflectionClass->isSubclassOf($class);
    }

    public function execute($object, $methodName, $args=[])
    {
        $method = $this->_reflectionClass->getMethod($methodName);
        return $method->invokeArgs($object, $args);
    }
}