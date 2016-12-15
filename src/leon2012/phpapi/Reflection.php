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

    const IS_STATIC = ReflectionProperty::IS_STATIC;        //1
    const IS_PUBLIC = ReflectionProperty::IS_PUBLIC;        //256
    const IS_PROTECTED = ReflectionProperty::IS_PROTECTED;  //512
    const IS_PRIVATE = ReflectionProperty::IS_PRIVATE;      //1024

    const IS_STRING = 2048; //property is string
    const IS_INT = 4096;    //property is int
    const IS_BOOL = 8192;   //property is bool
    const IS_FLOAT = 16384; //property is float
    const IS_NULL = 32768;  //property is null
    const IS_UNKNOWN = 0;   //property is unknown

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
     *
     */
    public function getObj()
    {
        return $this->_obj;
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
     * @param  array $args
     * @return mixed
     */
    public function execute($methodName, $args=[])
    {
        $method = $this->_reflectionClass->getMethod($methodName);

        return $method->invokeArgs($this->_obj, $args);
    }

    public function getProperties($filter)
    {
        $props = $this->_reflectionClass->getProperties($filter);

        return $props;
    }

    public function getPropertyNames($filter)
    {
        $props = $this->getProperties($filter);
        print_r($props);
        $names = [];
        foreach ($props as $prop) {
            $names[] = $prop->getName();
        }

        return $names;
    }

    public function getProperty($name)
    {
        return $this->_reflectionClass->getProperty($name);
    }

    public function getPropertyValue($name)
    {
        $prop = $this->getProperty($name);
        if ($prop) {
            return $prop->getValue();
        } else {
            return null;
        }
    }

    public function getPropertyType($name)
    {
        $propValue = $this->getPropertyValue($name);
        if (is_null($propValue)) {
            return Reflection::IS_NULL;

        } elseif (is_string($propValue)) {
            return Reflection::IS_STRING;

        } elseif (is_bool($propValue)) {
            return Reflection::IS_BOOL;

        } elseif (is_double($propValue)) {
            return Reflection::IS_FLOAT;

        } elseif (is_float($propValue)) {
            return Reflection::IS_FLOAT;

        } elseif (is_numeric($propValue)) {
            return Reflection::IS_FLOAT;

        } elseif (is_int($propValue)) {
            return Reflection::IS_INT;

        } elseif (is_long($propValue)) {
            return Reflection::IS_INT;

        } elseif (is_integer($propValue)) {
            return Reflection::IS_INT;

        } elseif (is_array($propValue)) {
            return Reflection::IS_UNKNOWN;

        } elseif (is_resource($propValue)) {
            return Reflection::IS_UNKNOWN;

        } elseif (is_callable($propValue)) {
            return Reflection::IS_UNKNOWN;

        } elseif (is_object($propValue)) {
            return Reflection::IS_UNKNOWN;
        } else {
            return Reflection::IS_UNKNOWN;
        }
    }

    public function getPropertyFormatValue($name)
    {

    }

    public function setPropertyValue($name, $value)
    {
        $prop = $this->getProperty($name);
        if ($prop) {
            $prop->setValue($value);

            return true;
        } else {
            return false;
        }
    }

}
