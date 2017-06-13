<?php
/**
 * @Author: PengYe
 * @Date:   2017-05-19 17:54:37
 * @Last Modified by:   PengYe
 * @Last Modified time: 2017-05-19 18:20:20
 */

namespace leon2012\phpapi\hooks;

use leon2012\phpapi\exceptions\InvalidArgumentException;

final class Hook
{
	
	private $_controllerHooks;
	private $_applicationHooks;
	private $_modelHooks;
	private $_points;

	public function __construct()
	{
		$this->_controllerHooks = [];
		$this->_applicationHooks = [];
		$this->_modelHooks = [];

		$this->_points = [
		'application' => [
			''
		], 
		'controller' => [
		],
		];
	}

	public function addControllerHook($class)
	{	
		$this->addHook("controller", $class);
	}

	public function getControllerHook()
	{

	}

	private function addHook($name, $class)
	{
		if (!in_array($name, $this->_names)) {
			throw new InvalidArgumentException('error name in hook');
		}
		
	}
}
