<?php
/**
 * @Author: pengleon
 * @Date:   2016-09-17 09:36:27
 * @Last Modified by:   PengLeon
 * @Last Modified time: 2016-09-17 09:37:44
 */

namespace leon2012\phpapi\traits;

trait DebugTrait 
{
	
	public function dump($value)
	{
		if (is_string($value)) {
	        echo $value;
	    }else if (is_array($value)) {
	        echo "<pre>";
	        print_r($value);
	        echo "</pre>";
	    }else{
	        echo $value;
	    }
	}
}
