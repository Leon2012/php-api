<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-02 11:47:01
 * @version $Id$
 */

use leon2012\phpapi\Collection;
use leon2012\phpapi\collections\HeaderCollection;


class HeaderCollectionTest extends PHPUnit_Framework_TestCase 
{
    
    public function testSetGet()
    {
        $header = new HeaderCollection();
        $header->set('a', 'a');
        $header->set('b', 'b');
        $header->set('c', 'c');
        $header->set('d', 'd');

        $this->assertEquals('a', $header->get('a'));
        $this->assertEquals('b', $header->get('a'));
    }
}