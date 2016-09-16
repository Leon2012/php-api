<?php
/**
 * @Author: PengLeon
 * @Date:   2016-09-16 15:50:11
 * @Last Modified by:   PengLeon
 * @Last Modified time: 2016-09-16 16:00:31
 */

namespace leon2012\phpapi\validations;

class StringValidator extends leon2012\phpapi\Validator
{

	public $min;
	public $max;
	public $length;

	public function __construct($length=[], $message = '')
	{
		if (!empty($length) && count($length) == 2) {
			$this->min = $length[0];
			$this->max = $length[1];
			$this->length = $length;
		}
		if (empty($message)) {
			$this->message = '';
		}else{
			$sthis->message = $message;
		}
	}

	public function valid()
	{
		
	}
}