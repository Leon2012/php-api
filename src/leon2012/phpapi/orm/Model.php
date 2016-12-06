<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */
namespace leon2012\phpapi\orm;
use leon2012\phpapi\Reflection;
use leon2012\phpapi\orm\exceptions\CoreException;

abstract class Model  
{

    private $_database;
    private $_pkId;
    private $_tableName;
    private $_reflection;
    
    public function __construct()
    {
        $this->_reflection = null; 
        $this->_database = null;
        $this->setPkId();
        $this->setTableName();
        $this->setDatabase();
    }

    public function C()
    {
        
    }

    public function U()
    {

    }

    public function R()
    {

    }

    public function D()
    {

    }

    private function getReflection()
    {
        if (is_null($this->_reflection)) {
            $this->_reflection = new Reflection($this);
        }
        return $this->_reflection;
    }

    private function setTableName()
    {
        $tableName = '';
        if ($this->getReflection()->hasMethod('tableName')) {
            $tableName = $this->getReflection()->exec($this, 'tableName');
        }else{
            $className = get_class($this);
            $pos = strpos($className, '\\');
            if ($pos) {
                $names = explode('\\', $className);
                $className = end($names);
            }
            $names = preg_split('/([[:upper:]][[:lower:]]+)/', $className, null, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
            $tableName = implode('_', $names);
        }
        if (empty($tableName)) {
            throw new CoreException('Invalid Table Name');
        }
        $this->_tableName = $tableName;
    }

    private function setPkId()
    {
        $pkId = '';
        if ($this->getReflection()->hasMethod('pkId')) {
            $pkId = $this->getReflection()->exec($this, 'pkId');
        }else{
            $pkId = 'id';
        }
        if (empty($pkId)) {
            throw new CoreException('Invalid Primary ID');
        }
        $this->_pkId = $pkId;
    }

    private function setDatabase()
    {
        $db = null;
        if ($this->getReflection()->hasMethod('database')) {
            $db = $this->getReflection()->exec($this, 'database');
        }
        if (is_null($db)) {
            throw new CoreException('Not init Database');
        }
        $this->_database = $db;
    }

    abstract protected function database();
    abstract protected function tableName();
    abstract protected function pkId();
}