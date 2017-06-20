<?php
/**
 * @Author: PengYe
 * @Date:   2017-06-19 11:03:51
 * @Last Modified by:   PengYe
 * @Last Modified time: 2017-06-20 10:36:25
 */
namespace leon2012\phpapi;

use ArrayAccess;
use Closure;
use Exception;
use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;

class Container implements ArrayAccess
{

	private $_definitions;
	private $_instances;
	private $_parameters;

	/**
	 * construct
	 */
	public function __construct()
	{
		$this->_definitions = [];
		$this->_instances = [];
		$this->_parameters = [];
	}

	/**
	 * set class by name
	 * @param string $name   
	 * @param class  $class  
	 * @param array  $params 
	 */
	public function set($name, $class = null, $params = [])
	{
		if (isset($name) && isset($class)) {
			if (!class_exists($class)) {
				throw new Exception("$class is not exist");
			}else{
				$this->_definitions[$name] = $class;
				if (!empty($params)) {
					$this->_parameters[$name] = $params;
				}
			}
		}else{
			if (!class_exists($name)) {
				throw new Exception("$name is not exist");
			}else{
				$this->_definitions[$name] = $name;
			}
		}
	}

	/**
	 * get instance by name
	 * @param  string $name 
	 * @return mixed 
	 */
	public function get($name)
	{
		if(isset($this->_instances[$name])) {
			return $this->_instances[$name];
		}else if (isset($this->_definitions[$name])) {
			return $this->resolve($name);
		}else{
			return null;
		}
	}

	/**
	 * set instance by name
	 * @param string $name     
	 * @param object|closure $instance 
	 */
	public function setInstance($name, $instance)
	{
		if(is_object($instance) || $instance instanceof Closure) {
			$this->_instances[$name] = $instance;
		}else{
			throw new Exception("$instance is not object or closure");
		}
	}

	/**
	 * call function
	 * @param  method|function $callback   
	 * @param  array  $parameters 
	 * @return mixed             
	 */
	public function call($callback, array $parameters = [])
	{
		$dependencies = $this->getMethodDependencies($callback);
		$instances = [];
		foreach ($dependencies as $dependency) {
			$this->set($dependency);
			$instances[$dependency] = $this->resolve($dependency);
		}
		$parameters = array_merge($instances, $parameters);
		return call_user_func_array($callback, $parameters);
	}

	/**
	 * resolve
	 * @param  string $name 
	 * @return object
	 */
	protected function resolve($name)
	{
		$class = $this->_definitions[$name];
		$parameters = isset($this->_parameters[$name])?$this->parameters[$name]:[];
		$reflection = new ReflectionClass($class);
		if (!$reflection->isInstantiable()) {
			throw new Exception("$class is not instantiable");
		}
		$dependencies = $this->getDependencies($class);
		$instances = [];
		foreach ($dependencies as $key => $class) {
			$offset = is_string($key) ? $key : $class;
			if(isset($this->_instances[$offset])) {
				$instances[$offset] = $this->instances[$offset];
			}else{
				$this->set($offset, $class);
				$instances[$offset] = $this->get($offset);
			}
		}
		$parameters = array_merge($instances, $parameters);
		$object = $reflection->newInstanceArgs($parameters);
		$this->setInstance($name, $object);
		return $object;
	}

	/**
	 * get method dependencies
	 * @param  method|function $callback 
	 * @return array
	 */
	protected function getMethodDependencies($callback)
	{
		$dependencies = [];
		if(is_array($callback)) {
			$method = new ReflectionMethod($callback[0], $callback[1]);
		}else{
			$method = new ReflectionFunction($callback);
		}
		if ($method != null) {
			foreach ($method->getParameters() as $param) {
				if ($param->getClass()) {
					$dependencies[] = $param->getClass()->getName();
				}
			}
		}
		return $dependencies;
	}

	/**
	 * get class dependencies
	 * @param  string $class 
	 * @return array
	 */
	protected function getDependencies($class)
	{
		$dependencies = [];
		$reflection = new ReflectionClass($class);
		$constructor = $reflection->getConstructor();
		if ($constructor != null) {
			foreach ($constructor->getParameters() as $param) {
				if ($param->getClass()) {
					$dependencies[] = $param->getClass()->getName();
				}
			}
		}
		return $dependencies;
	}

	/**
	 * clear
	 * @return void
	 */
	public function clear()
    {
        $this->_definitions = [];
        $this->_instances = [];
        $this->_parameters = [];
    }


    /**
     * implement offsetExists offsetGet
     * @param  string $key 
     * @return boolean     
     */
    public function offsetExists($key)
    {
        return (isset($this->_definitions[$key]) || isset($this->_instances[$key]));
    }

    /**
     * implement ArrayAccess offsetGet
     * @param  string $key 
     * @return mixed    
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * implement ArrayAccess offsetSet
     * @param  string $key   
     * @param  closure|object $value 
     * @return void        
     */
    public function offsetSet($key, $value)
    {
        return $this->setInstance($key, $value);
    }

    /**
     * implement ArrayAccess offsetUnset
     * @param  string $key 
     * @return void    
     */
    public function offsetUnset($key)
    {
        unset($this->_definitions[$key]);
        unset($this->_instances[$key]);
        unset($this->_parameters[$key]);
    }
}
