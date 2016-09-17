<?php
/**
 * @Author: PengLeon
 * @Date:   2016-09-17 09:46:53
 * @Last Modified by:   PengLeon
 * @Last Modified time: 2016-09-17 09:52:49
 */

class YieldTest extends PHPUnit_Framework_TestCase
{

	public $arr = [1, 2, 3];

	public function testRange()
	{
		foreach($this->range() as $k => $v) {
			echo $k.'=>'.$v."\n";
		}
	}

	public function range()
	{
		foreach($this->arr as $k => $v) {
			yield $k => $v;
		}
	}
}
