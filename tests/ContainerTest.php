<?php
/**
 * @Author: PengYe
 * @Date:   2017-06-19 13:57:05
 * @Last Modified by:   PengYe
 * @Last Modified time: 2017-06-19 14:03:24
 */

use leon2012\phpapi\Container;

class Foo{
	public $bar;
}

class Bar {};
class FooBar {};

class ContainerTest extends PHPUnit_Framework_TestCase
{


	public function testInit()
	{
		$foo = new Foo();
		$container = new Container();
		$container->set("foo", 'Foo');
		$f = $container->get("foo");
		var_dump($f);

		$container->setInstance("bar", function(){
			return new Bar();
		});
		$b = $container->get("bar");
		var_dump($b);
	}

}
