<?php
/**
 * @Author: PengYe
 * @Date:   2017-05-27 14:42:42
 * @Last Modified by:   PengYe
 * @Last Modified time: 2017-05-27 14:49:18
 */

namespace leon2012\phpapi;

class Benchmark
{

	private $_mark;

	public function __construct()
	{
		$this->_mark = [];
	}

	public function mark($name)
	{
		$this->_mark[$name] = microtime(true);
	}

	public function calc($point1, $point2, $decimals = 4)
	{
		if (!isset($this->_mark[$point1])) {
			return '';
		}
		if (!isset($this->_mark[$point2])) {
			$this->_mark[$point2] = microtime(true);
		}
		return number_format($this->_mark[$point2] - $this->_mark[$point1], $decimals);
	}

	public function memoryUsage()
	{
		return memory_get_usage();
	}
}
