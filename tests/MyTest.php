<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-01 09:56:01
 * @version $Id$
 */


class MyTest extends PHPUnit_Framework_TestCase 
{
    
    public function testOne()
    {
        $this->assertEquals(0, count(array()));
    }

    public function testTwo()
    {
        $this->assertEquals(0, count(array('a')));
    }
}