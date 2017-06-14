<?php
/**
 * @Author: PengYe
 * @Date:   2017-06-14 10:53:45
 * @Last Modified by:   PengYe
 * @Last Modified time: 2017-06-14 11:24:02
 */

use leon2012\phpapi\Route;

class RouteTest extends PHPUnit_Framework_TestCase
{
	
	public function testInit()
	{
		$route = new Route('GET', '/site/index/{:id}', 'aaaa:aaaa');
	}


}
