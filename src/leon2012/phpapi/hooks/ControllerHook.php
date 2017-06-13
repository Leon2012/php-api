<?php
/**
 * @Author: PengYe
 * @Date:   2017-05-19 17:47:57
 * @Last Modified by:   PengYe
 * @Last Modified time: 2017-05-19 18:18:34
 */

namespace leon2012\phpapi\hooks;

interface ControllerHook
{
	public function onBeforeAction($controller, $action, $params);
	public function onAfterAction($controller, $action, $params);
}