<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-02 11:22:40
 * @version $Id$
 */

namespace leon2012\phpapi;

use ArrayIterator;

abstract  class Collection implements \IteratorAggregate, \ArrayAccess, \Countable
{
    
    private $_data;

    public function __construct()
    {
        $this->_data = [];
    }

    abstract protected function initData();

    public function get($name, $defaultValue = null)
    {
        if (!isset($this->_data[$name])) {
            return $defaultValue;
        }
        return $this->_data[$name];
    }

    public function set($name, $value = '')
    {
        $this->_data[$name] = $value;
        return $this;
    }

    public function add($name, $value)
    {
        return $this->set($name, $value);
    }

    public function fromArray($arr)
    {
        $this->_data = $arr;
    }

    public function has($name)
    {
        return isset($this->_data[$name]);
    }

    public function remove($name)
    {
        if (isset($this->_data[$name])) {
            $value = $this->_data[$name];
            unset($this->_data[$name]);
            return $value;
        }else{
            return null;
        }
    }

    public function removeAll()
    {
        $this->_data = [];
    }

    /**
     * implment Countable count
     */
    public function count() 
    { 
        return count($this->_data); 
    } 

    /**
     * implment IteratorAggregate getIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->_data);
    }

    /**
     * implment ArrayAccess offsetSet
     */
    public function offsetSet($name, $value) 
    {
        $this->set($name, $value);
    }

    /**
     * implment ArrayAccess offsetExists
     */
    public function offsetExists($name) {
        return $this->has($name);
    }

    /**
     * implment ArrayAccess offsetUnset
     */
    public function offsetUnset($name) 
    {
        $this->remove($name);
    }

    /**
     * implment ArrayAccess offsetGet
     */
    public function offsetGet($name) 
    {
        return $this->get($name);
    }
}