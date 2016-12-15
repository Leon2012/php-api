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

abstract class Model implements \IteratorAggregate, \ArrayAccess, \Countable
{
    protected $_database;
    protected $_pkId;
    protected $_tableName;
    protected $_charset;
    protected $_fieldTypes;
    protected $_properties;

    public function __construct($database = null)
    {
        $this->_database = $database;
        $this->_charset = 'UTF8';
        $this->_properties = [];
        $this->setFieldTypes();
    }

    public function getPkId()
    {
        if (empty($this->_pkId)) {
            $this->setPkId();
        }
        return $this->_pkId;
    }

    public function getPkValue()
    {
        $pkValue = $this->getFieldValue($this->getPkId(), Reflection::IS_INT);
        return $pkValue;
    }

    public function getTableName()
    {
        if (empty($this->_tableName)) {
            $this->setTableName();
        }
        return $this->_tableName;
    }

    public function one($data = null)
    {
        $params = [];
        if (is_null($data)) {
            $pkValue = $this->getPkValue();
            $params = [$this->getPkId() => $pkValue];
        } elseif (is_array($data)) {
            foreach ($data as $propName => $propValue) {
                $params[$propName] = $value;
            }
        } elseif (is_int($data)) {
            $params = [$this->getPkId() => $data];
        } elseif (is_string($data)) {
            $params = [$this->getPkId() => intval($data)];
        }
        $results = $this->database()->one($this->getTableName(), $params, [], true);
        if ($results === false) {
            return false;
        } else {
            foreach ($results as $k => $v) {
                $this->$k = $v;
            }

            return true;
        }
    }

    public function create()
    {
        $propNames = $this->getReflection()->getPropertyNames(Reflection::IS_PUBLIC);
        $sql = '';
        $sql .= 'CREATE TABLE `'.$this->getTableName().'` {';

        $sql .= '} ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET='.$this->getDatabaseCharset();
    }

    public function update($data = null)
    {
        $sql = '';
        $sql .= ' UPDATE '.$this->getTableName();
        $sql .= ' SET ';
        $fieldStr = '';
        if (is_array($data) && !empty($data)) {
            $fields = [];
            foreach ($data as $propName => $propValue) {
                $propType = self::getValueType($propValue);
                $value = $this->getFieldValue($propName, $propType);
                $fields[] .= " `".$propName."` = ".$propValue;
            }
            $fieldStr = implode(" , ", $fields);
        } else {
            $allFields = $this->getFields();
            $fields = [];
            foreach ($allFields as $propName => $propType) {
                $fields[] .= " `".$propName."` = ".$this->getFieldValue($propName, $propType);
            }
            $fieldStr = implode(" , ", $fields);
        }
        $sql .= $fieldStr;
        $pkValue = $this->getPkValue();
        $sql .= " WHERE `".$this->getPkId()."` = ".$pkValue;

        return $this->database()->exec($sql);
    }

    public function delete($data = null)
    {
        $sql = '';
        $sql .= ' DELETE FROM ' . $this->getTableName();
        $sql .= ' WHERE ';
        $where = '';
        if (is_null($data)) {
            $pkValue = $this->getPkValue();
            $where = "`".$this->getPkId()."`='".$pkValue."'";
        } elseif (is_array($data)) {
            $fields = [];
            foreach ($data as $propName => $propValue) {
                $propType = self::getValueType($propValue);
                $value = $this->getFieldValue($propName, $propType);
                $fields[] = "`".$propName."` = ".$value;
            }
            $where = implode(" AND ", $fields);
        } elseif (is_int($data)) {
            $where = "`".$this->getPkId()."`='".$data."'";
        } elseif (is_string($data)) {
            $where = "`".$this->getPkId()."`='".intval($data)."'";
        }
        if (empty($where)) {
            $where = " 1=1 ";
        }
        $sql .= $where;

        return $this->database()->exec($sql);
    }

    public function insert($data = null)
    {
        $sql = '';
        $sql .= ' INSERT INTO '.$this->getTableName();
        $sql .= ' ( ';
        if (is_array($data) && !empty($data)) {
            $names = [];
            $values = [];
            foreach ($data as $propName => $propValue) {
                $names[] = "`".$propName."`";
                $propType = self::getValueType($propValue);
                $values[] = $this->formatPropertyValue($propValue, $propType);
            }
            $nameStr = implode(",", $names);
            $valueStr = implode(",", $values);
        } else {
            $fields = $this->getFields();
            $names = [];
            $values = [];
            foreach ($fields as $propName => $propType) {
                $names[] = " `".$propName."` ";
                $value = $this->getFieldValue($propName, $propType);
                $values[] = $value;
            }
            $nameStr = implode(",", $names);
            $valueStr = implode(",", $values);
        }
        $sql .= $nameStr;
        $sql .=' ) ';
        $sql .= ' VALUES ( ';
        $sql .= $valueStr;
        $sql .= ' ) ';

        return $this->database()->exec($sql);
    }

    public function getTablePrefix()
    {
        return $this->database()->getConfig('tablePrefix');
    }

    public function getDatabaseCharset()
    {
        $charset = $this->database()->getConfig('charset');
        if (isset($charset) && !empty($charset)) {
            $this->_charset = strtoupper($charset);
        }

        return $this->_charset;
    }

    public function __debugInfo()
    {
        return [
            'tableName' => $this->getTableName(),
            'pkId' => $this->getPkId(),
        ];
    }

    public function __set($name, $value)
    {
        $this->_properties[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->_properties)) {
            return $this->_properties[$name];
        }

        return null;
    }

    public function __unset($name)
    {
        unset($this->_properties[$name]);
    }

    public function __isset($name)
    {
        return isset($this->_properties[$name]);
    }

    private function getPropertyNames()
    {
        return array_keys($this->_properties);
    }

    public static function getValueType($propValue)
    {
        if (is_null($propValue)) {
            return Reflection::IS_NULL;
        } elseif (is_string($propValue)) {
            return Reflection::IS_STRING;
        } elseif (is_bool($propValue)) {
            return Reflection::IS_BOOL;
        } elseif (is_double($propValue)) {
            return Reflection::IS_FLOAT;
        } elseif (is_float($propValue)) {
            return Reflection::IS_FLOAT;
        } elseif (is_numeric($propValue)) {
            return Reflection::IS_FLOAT;
        } elseif (is_int($propValue)) {
            return Reflection::IS_INT;
        } elseif (is_long($propValue)) {
            return Reflection::IS_INT;
        } elseif (is_integer($propValue)) {
            return Reflection::IS_INT;
        } elseif (is_array($propValue)) {
            return Reflection::IS_UNKNOWN;
        } elseif (is_resource($propValue)) {
            return Reflection::IS_UNKNOWN;
        } elseif (is_callable($propValue)) {
            return Reflection::IS_UNKNOWN;
        } elseif (is_object($propValue)) {
            return Reflection::IS_UNKNOWN;
        } else {
            return Reflection::IS_UNKNOWN;
        }
    }

    private function formatPropertyValue($value, $propType)
    {
        $formatValue = "";
        switch ($propType) {
            case Reflection::IS_STRING:
                $formatValue = $this->database()->quote($value);
            break;
            case Reflection::IS_INT:
                $formatValue = $value;
            break;
            case Reflection::IS_BOOL:
                if (true == $value) {
                    $formatValue = 1;
                } else {
                    $formatValue = 0;
                }
            break;
            case Reflection::IS_FLOAT:
                $formatValue = $value;
            break;
            case Reflection::IS_NULL:
                $formatValue = "";
            break;
        }

        return $formatValue;
    }

    private function getPropertyValue($name)
    {
        if (isset($this->_properties[$name])) {
            return $this->_properties[$name];
        }

        return null;
    }

    private function getPropertyType($name)
    {
        $propValue = $this->getPropertyValue($name);

        return self::getValueType($propValue);
    }

    private function getFields()
    {
        $fields = [];
        $propNames = $this->getPropertyNames();
        foreach ($propNames as $idx => $propName) {
            $propType = $this->getPropertyType($propName);
            if ($propType != Reflection::IS_UNKNOWN) {
                $fields[$propName] = $propType;
            }
        }

        return $fields;
    }

    private function getFieldValue($fieldName, $propType)
    {
        $value = $this->getPropertyValue($fieldName);

        return $this->formatPropertyValue($value, $propType);
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

    private function getReflection()
    {
        return ReflectionManager::shareManager()->getReflection($this);
    }

    private function setTableName()
    {
        $tableName = '';
        if ($this->getReflection()->hasMethod('tableName')) {
            $tableName = $this->getReflection()->execute('tableName');
        } else {
            $className = get_class($this);
            $pos = strpos($className, '\\');
            if ($pos) {
                $names = explode('\\', $className);
                $className = end($names);
            }
            $names = preg_split('/([[:upper:]][[:lower:]]+)/', $className, null, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
            if (end($names) == "Model") {
                $names = array_slice($names, 0, -1);
            }
            $tableName = implode('_', $names);
            $tableName = strtolower($tableName);
        }
        if (empty($tableName)) {
            throw new CoreException('Invalid Table Name');
        }
        $tablePrefix = $this->getTablePrefix();
        $pos = strpos($tableName, $tablePrefix);
        if ($pos === false) {
            $this->_tableName = $tablePrefix.$tableName;
        } else {
            $this->_tableName = $tableName;
        }
    }

    private function setPkId()
    {
        $pkId = '';
        if ($this->getReflection()->hasMethod('pkId')) {
            $pkId = $this->getReflection()->execute('pkId');
        } else {
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
            $db = $this->getReflection()->execute('database');
        }
        if (is_null($db)) {
            throw new CoreException('Not init Database');
        }
        $this->_database = $db;
    }

    // abstract protected function database();
    // abstract protected function tableName();
    // abstract protected function pkId();

    /**
     * implment Countable count
     */
    public function count()
    {
        return count($this->_properties);
    }

    /**
     * implment IteratorAggregate getIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->_properties);
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
    public function offsetExists($name)
    {
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

    /**
     * @param $name
     * @param  null       $defaultValue
     * @return mixed|null
     */
    public function get($name, $defaultValue = null)
    {
        if (!isset($this->_properties[$name])) {
            return $defaultValue;
        }

        return $this->_properties[$name];
    }

    /**
     * @param $name
     * @param  string $value
     * @return $this
     */
    public function set($name, $value = '')
    {
        $this->_properties[$name] = $value;
        return $this;
    }

    /**
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->_properties[$name]);
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function remove($name)
    {
        if (isset($this->_properties[$name])) {
            $value = $this->_properties[$name];
            unset($this->_properties[$name]);
            return $value;
        } else {
            return null;
        }
    }
}
