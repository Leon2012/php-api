<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-12 15:18:44
 * @version $Id$
 */

namespace leon2012\phpapi;

class Module 
{
    public $controllerNamespace;
    public $defaultRoute = 'default/index';
    private $_basePath;

    /**
     * Module constructor.
     */
    public function __construct()
    {
        
    }

    /**
     *
     */
    public function init()
    {
        if ($this->controllerNamespace === null) {
            $class = get_class($this);
            if (($pos = strrpos($class, '\\')) !== false) {
                $this->controllerNamespace = substr($class, 0, $pos) . '\\controllers';
            }
        }
    }

    /**
     * @return mixed
     */
    public function getControllerPath()
    {
        return str_replace('\\', '/', $this->controllerNamespace);
    }


}