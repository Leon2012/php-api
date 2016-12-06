<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */

namespace leon2012\phpapi;

abstract class Validator
{

	private 	$_errors;
	public 		$message;
	public 		$attribute;

    /**
     * Validator constructor.
     */
    public function __construct()
	{
		$this->_errors = [];
	}

    /**
     * @return array
     */
    public function getErrors()
	{
		return $this->_errors;
	}

    /**
     * @return mixed
     */
    public function getLastError()
	{
		return end($this->_errors);
	}

    /**
     * @param $attribute
     * @param $message
     */
    public function addError($attribute, $message)
	{
		$error = str_replace($message, '{'.$attribute.'}', $attribute);
		$this->_errors[] = $error;
	}


    abstract function valid();
}
