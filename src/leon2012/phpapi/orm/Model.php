<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */
namespace leon2012\phpapi\orm;
use leon2012\phpapi\Reflection;
use leon2012\phpapi\ReflectionManager;
use leon2012\phpapi\orm\exceptions\CoreException;

abstract class Model  
{
    private $_database;
    private $_pkId;
    private $_tableName;
    private $_charset;
    private $_fieldTypes;

    public function __construct()
    {
        $this->_database = null;
        $this->_charset = 'UTF8';
        $this->setFieldTypes();
        $this->setPkId();
        $this->setTableName();
        $this->setDatabase();
    }

    public function C()
    {
        $fullTableName = $this->getTablePrefix().$this->_tableName;
        $propNames = $this->getReflection()->getPropertyNames(Reflection::IS_PUBLIC);
        $sql = '';
        $sql .= 'CREATE TABLE `'.$fullTableName.'` {';

        $sql .= '} ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET='.$this->getDatabaseCharset();
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

    private function getFields()
    {
        $fields = [];
        $propNames = $this->getReflection()->getPropertyNames(Reflection::IS_PUBLIC);
        foreach($propNames as $propName) {
            $propType = $this->getReflection()->getPropertyType($propName);
            if ($propType != Reflection::IS_UNKNOWN) {
                $fields[$propName] = $propType;
            }
        }
    }

    private function setFieldTypes()
    {
        $this->_fieldTypes = [
            Reflection::IS_STRING => [
                'type' => 'varchar',  //sql type
                'length' => '256',    //default length
                'decimal' => '0',     //default decimal length
                'value' => '',        //default value
                'isNull' => false,    //null  
                'comment' => '',      //comment
            ],
            Reflection::IS_INT => [
                'type' => 'int',  
                'length' => '11',    
                'decimal' => '0',     
                'value' => '0',        
                'isNull' => false,    
                'comment' => '',      
            ],
            Reflection::IS_BOOL => [
                'type' => 'smallint',
                'length' => '1',
                'decimal' => '0',     
                'value' => '0',        
                'isNull' => false,    
                'comment' => '',
            ],
            Reflection::IS_FLOAT => [
                'type' => 'decimal',
                'length' => '10',
                'decimal' => '2',     
                'value' => '0.00',        
                'isNull' => false,    
                'comment' => '',
            ],
            Reflection::IS_NULL => [
                'type' => 'varchar',
                'length' => '255',
                'decimal' => '0',     
                'value' => '',        
                'isNull' => true,    
                'comment' => '',
            ],
        ];
    }

    public function getTablePrefix()
    {
        return $this->_database->getConfig('tablePrefix');
    }

    public function getDatabaseCharset()
    {
        $charset = $this->_database->getConfig('charset');
        if (isset($charset) && !empty($charset)) {
            $this->_charset = strtoupper($charset);
        }
        return $this->_charset;
    }

    private function getReflection()
    {
        return ReflectionManager::shareManager()->getReflection($this);
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