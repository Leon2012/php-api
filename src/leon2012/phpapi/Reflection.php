<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */

namespace leon2012\phpapi;

use ReflectionClass;
use ReflectionProperty;

class Reflection
{
    private $_reflectionClass;
    private $_obj;

    /**
     * Reflection constructor.
     * @param $class
     */
    public function __construct($obj)
    {
       $this->_reflectionClass = new ReflectionClass($obj);
       $this->_obj = $obj;
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
    public function execute($methodName, $args=[])
    {
        $method = $this->_reflectionClass->getMethod($methodName);
        return $method->invokeArgs($this->_obj, $args);
    }
}