<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */

namespace leon2012\phpapi;

use ArrayIterator;

abstract  class Collection implements \IteratorAggregate, \ArrayAccess, \Countable
{
    
    private $_data;

    /**
     * Collection constructor.
     */
    public function __construct()
    {
        $this->_data = [];
    }

    /**
     * @param $name
     * @param null $defaultValue
     * @return mixed|null
     */
    public function get($name, $defaultValue = null)
    {
        if (!isset($this->_data[$name])) {
            return $defaultValue;
        }
        return $this->_data[$name];
    }

    /**
     * @param $name
     * @param string $value
     * @return $this
     */
    public function set($name, $value = '')
    {
        $this->_data[$name] = $value;
        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return Collection
     */
    public function add($name, $value)
    {
        return $this->set($name, $value);
    }

    /**
     * @param $arr
     */
    public function fromArray($arr)
    {
        $this->_data = $arr;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->_data;
    }

    /**
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->_data[$name]);
    }

    /**
     * @param $name
     * @return mixed|null
     */
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

    /**
     *
     */
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