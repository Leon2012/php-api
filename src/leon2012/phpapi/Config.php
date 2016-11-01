<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-02 17:03:51
 * @version $Id$
 */

namespace leon2012\phpapi;

use leon2012\phpapi\exceptions\CoreException;
use leon2012\phpapi\Response;

class Config 
{
    private $_id;
	private $_appPath;
	private $_controllerNamespace;
    private $_defaultRoute;
    private $_modules;
    private $_data = [];
    private $_outputFormat;
	/**
	 * init object
	 * @param array $config [description]
	 */
    public function __construct($config = [])
    {
        $this->fromArray($config);
    }

    /**
     * init data
     * @param  array  $arr [description]
     * @return [type]      [description]
     */
    public function fromArray($arr = [])
    {
    	$this->_id = isset($arr['id'])?$arr['id']:'';
 		$this->_appPath = isset($arr['appPath'])?$arr['appPath']:'';
 		$this->_controllerNamespace = isset($arr['controllerNamespace'])?$arr['controllerNamespace']:'';
        $this->_defaultRoute = isset($arr['defaultRoute'])?$arr['defaultRoute']:'';
        $this->_outputFormat = isset($arr['outputFormat'])?strtoupper($arr['outputFormat']):'';

        $this->_modules = isset($arr['modules'])?$arr['modules']:[];
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
     * valid 
     * @return [type] [description]
     */
    public function valid()
    {
    	if (empty($this->_id)) {
    		throw new CoreException('Id is invalid');
    	}

    	if (empty($this->_appPath)) {
            throw new CoreException('AppPath is invalid');
        }

        if (empty($this->_controllerNamespace)) {
            throw new CoreException('ControllerNamespace is invalid');
        }

        if (empty($this->_defaultRoute)) {
            throw new CoreException('DefaultRoute is invalid');
        }

        if (!in_array($this->_outputFormat, Response::outputFormats())) {
            throw new CoreException('OutputFormat is invalid');
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return mixed
     */
    public function getAppPath()
    {
        return $this->_appPath;
    }

    /**
     * @return mixed
     */
    public function getControllerNamespace()
    {
        return $this->_controllerNamespace;
    }

    /**
     * @return mixed
     */
    public function getDefaultRoute()
    {
        return $this->_defaultRoute;
    }

    /**
     * @return mixed
     */
    public function getModules()
    {
        return $this->_modules;
    }

    /**
     * @return mixed
     */
    public function getOutputFormat()
    {
        return $this->_outputFormat;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        $func = 'get'.ucfirst($name);
        if (method_exists($this, $func)) {
            return $this->{$func}();
        }
    }

    /**
     *
     */
    public function __clone()
    {
        $this->_id = clone $this->_id;
        $this->_appPath = clone $this->_appPath;
        $this->_controllerNamespace = clone $this->_controllerNamespace;
        $this->_defaultRoute = clone $this->_defaultRoute;
        $this->_modules = clone $this->_modules;
        $this->_data = clone $this->_data;
    }
}