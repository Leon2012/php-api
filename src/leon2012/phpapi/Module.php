<?php
/**
 *
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
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
