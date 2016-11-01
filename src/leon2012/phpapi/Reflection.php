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

    /**
     * Reflection constructor.
     * @param $class
     */
    public function __construct($class)
    {
       $this->setClass($class);
    }

    /**
     * @param $class
     */
    public function setClass($class)
    {
        if (is_object($class)) {
            $class = get_class($class);
        }
        $this->_reflectionClass = new ReflectionClass($class);
    }

    /**
     * @return mixed
     */
    public function getReflectionClass()
    {
        return $this->_reflectionClass;
    }

    /**
     * @param $methodName
     * @return mixed
     */
    public function hasMethod($methodName)
    {
        return $this->_reflectionClass->hasMethod($methodName);
    }

    /**
     * @param $methodName
     * @return array
     */
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

    /**
     * @return array
     */
    public function getParentClassNames()
    {
        $parents = array();
        while ($parent = $this->_reflectionClass->getParentClass()) {
            $parents[] = $parent->getName();
            $class = $parent;
        }
        return $parents;
    }

    /**
     * @param $object
     * @return mixed
     */
    public function isInstance($object)
    {
        return $this->_reflectionClass->isInstance($object);
    }

    /**
     * @param $class
     * @return mixed
     */
    public function isSubclassOf($class)
    {
        return $this->_reflectionClass->isSubclassOf($class);
    }

    /**
     * @param $object
     * @param $methodName
     * @param array $args
     * @return mixed
     */
    public function execute($object, $methodName, $args=[])
    {
        $method = $this->_reflectionClass->getMethod($methodName);
        return $method->invokeArgs($object, $args);
    }
}