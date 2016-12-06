<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */

use leon2012\phpapi\Collection;
use leon2012\phpapi\collections\HeaderCollection;


class HeaderCollectionTest extends PHPUnit_Framework_TestCase 
{
    
    public function testSetGet()
    {
        $header = new HeaderCollection();
        $header->initData();
        
        $header->set('a', 'a');
        $header->set('b', 'b');
        $header->set('c', 'c');
        $header->set('d', 'd');

        $this->assertEquals('a', $header->get('a'));
        $this->assertEquals('b', $header->get('a'));
    }
}