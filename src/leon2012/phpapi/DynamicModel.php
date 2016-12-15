<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-15 16:40:54
 * @version $Id$
 */
namespace leon2012\phpapi;


class DynamicModel extends Model 
{
    private $_name;

    public function __construct($name, $database = null)
    {
        parent::__construct($database);
        $this->_name = $name;
    }

    public function tableName()
    {
        return $this->_name;
    }
}