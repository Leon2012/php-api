<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-19 11:52:32
 * @version $Id$
 */
namespace leon2012\phpapi\cache\drivers;

class Base 
{

    protected $_driverName;

    public function __construct($driverName)
    {
        $this->_driverName = $driverName;
    }

    public function getDriverName()
    {
        return $this->_driverName;
    }
}