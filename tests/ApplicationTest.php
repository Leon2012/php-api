<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-01 10:22:37
 * @version $Id$
 */

use leon2012\phpapi\Application;

class ApplicationTest extends PHPUnit_Framework_TestCase 
{
    
    public function testInit()
    {
        $app = new leon2012\phpapi\Application();
    }

    public function testInstance()
    {
        $app = leon2012\phpapi\Application::getInstance();
        $this->assertEquals(NULL, $app);
    }

    public function testSetGet()
    {
        $app = leon2012\phpapi\Application::getInstance();
        $app->a = 'a';
        $app1 = leon2012\phpapi\Application::getInstance();
        $this->assertEquals('a', $app1->a);
    }

    public function testCall()
    {
        $app = Application::getInstance();
        $app->setName('name');
        $this->assertEquals('name', $app->getName());
    }

    public function testCall1()
    {
        $app = Application::new();
        $app->test();
    }
}