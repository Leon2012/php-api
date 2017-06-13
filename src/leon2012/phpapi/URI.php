<?php
/**
 * @Author: PengYe
 * @Date:   2017-05-27 14:50:47
 * @Last Modified by:   PengYe
 * @Last Modified time: 2017-06-13 17:55:01
 */

namespace leon2012\phpapi;

class URI
{
	private $_module;
	private $_contrller;
	private $_action;
	private $_params;
	private $_urlString;

	const PROTOCOL_REQUEST_URI = 1;
	const PROTOCOL_QUERY_STRING = 2;
	const PROTOCOL_PATH_INFO = 3;
	const PROTOCOL_AUTO = 0;

	public function __construct($protocol = 0)
	{
		$this->_params = [];
		switch($protocol) {
			case self::PROTOCOL_AUTO:
			case self::PROTOCOL_PATH_INFO:
				$this->parsePathInfo();
				break;
			case self::PROTOCOL_REQUEST_URI:
				$this->parseRequestUri();
				break;
			case self::PROTOCOL_QUERY_STRING:
				$this->parseQueryString();
				break;
		}
	}


	private function parseRequestUri()
	{
		if (!isset($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME'])){
			return '';
		}
		$uri = parse_url('http://dummy'.$_SERVER['REQUEST_URI']);
		$query = isset($uri['query']) ? $uri['query'] : '';
		$uri = isset($uri['path']) ? $uri['path'] : '';
	}

	private function parsePathInfo()
	{
		if (!isset($_SERVER['PATH_INFO'], $_SERVER['ORIG_PATH_INFO'])) {
			return '';
		}
	}

	private function parseQueryString()
	{
		$uri = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : @getenv('QUERY_STRING');
		if (trim($uri, '/') === ''){
			return '';
		}
		
	}

	private function setUriString($str)
	{
		$this->_urlString = trim($str, '/');
		if ($this->_urlString != '') {

		}
	}

}
