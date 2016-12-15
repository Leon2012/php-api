<?php
/**
 *
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */

class YieldTest extends PHPUnit_Framework_TestCase
{

    public $arr = [1, 2, 3];

    public function testRange()
    {
        foreach ($this->range() as $k => $v) {
            echo $k.'=>'.$v."\n";
        }
    }

    public function range()
    {
        foreach ($this->arr as $k => $v) {
            yield $k => $v;
        }
    }
}
