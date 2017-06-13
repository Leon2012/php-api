<?php
/**
 * @Author: PengYe
 * @Date:   2017-05-24 17:56:32
 * @Last Modified by:   PengYe
 * @Last Modified time: 2017-05-25 09:20:57
 */

namespace leon2012\phpapi\orm;

use leon2012\phpapi\orm\exceptions\CoreException;

class Query 
{
	private $_from;
	private $_limit;
	private $_offset;
	private $_where = [];
	private $_select = [];
	private $_orderBy = [];
	private $_groupBy = [];
	private $_having = [];
	private $_joins = [];
	private $_operators = ['OR', 'AND', 'NOT', 'NOT BETWEEN', 'NOT IN', 'OR LIKE', 'NOT LIKE', 'OR NOT LIKE', 'EXISTS', 'NOT EXISTS', 'BETWEEN', 'IN', 'LIKE', 'NOT IN'];

	public function __construct()
	{
		$this->_limit = 0;
		$this->_offset = 0;
	}

	/**
	 * 
	 * @param  string $table 
	 * @return self       
	 */
	public function from($table)
	{
		$this->_from = $table;
		return $this;
	}

	/**
	 * 
	 * @param  array|string $condition 
	 * @param  array  $params    
	 * @return self            
	 */
	public function where($condition, $params = [])
	{
		

		return $this;
	}

	/**
	 * 
	 * @param  array|string $select 
	 * @return self        
	 */
	public function select($select)
	{
		if (is_array($select)) {
			$this->_select = $select;
		}else if (is_string($select)) {
			$fields = explode(",", $select);
			$this->_select = $fields;
		}else{
			throw new CoreException("error select");
		}
		return $this;
	}

	public function limit($limit)
	{
		$this->_limit = $limit;
		return $this;
	}

	public function offset($offset)
	{
		$this->_offset = $offset;
		return $this;
	}


	public function toString()
	{

	}
}
