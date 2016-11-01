<?php
/**
 * @Author: PengLeon
 * @Date:   2016-09-16 15:47:02
 * @Last Modified by:   PengLeon
 * @Last Modified time: 2016-09-16 16:17:12
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

	abstruct /**
 *
 */
    function valid();
}
