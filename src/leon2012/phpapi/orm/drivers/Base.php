<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */
namespace leon2012\phpapi\orm\drivers;


abstract class Base  
{
    protected $_connection;
    protected $_config;
    protected $_lastSql;

    public function __construct()
    {
        $this->_connection = null;
        $this->_config = null;
        $this->_lastSql = '';
    }

    public function getLastSql()
    {
        return $this->_lastSql;
    }

    public function getConnection()
    {
        return $this->_connection;
    }

    public function getConfig()
    {
        return $this->_config;
    }

    protected function setLastSql($sql)
    {
        $this->_lastSql = $sql;
    }
}