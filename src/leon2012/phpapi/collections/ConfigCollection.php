<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-02 16:56:29
 * @version $Id$
 */
namespace leon2012\phpapi\collections;


class ConfigCollection extends \leon2012\phpapi\Collection  
{

    private $_config;

    public function __construct($config = [])
    {
        $this->_config = $config;
    }

    public  function initData()
    {
        foreach ($this->_config as $name => $value) {
            $this->add($name, $value);
        }
        $this->_config = null;
    }
}