<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
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

    public function testSplit()
    {
        $string = "HelloWorld"; 
        $arr = preg_split('/([[:upper:]][[:lower:]]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY); 
        print_r($arr); 
    }
}