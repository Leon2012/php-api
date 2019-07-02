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
	private $_urlString;
	private $_permittedUriChars = 'a-z 0-9~%.:_\-';
	private $_segments = [];

	const PROTOCOL_REQUEST_URI = 1;
	const PROTOCOL_QUERY_STRING = 2;
	const PROTOCOL_PATH_INFO = 3;
	const PROTOCOL_AUTO = 0;

	public function __construct($protocol = 0)
	{
		$this->_segments = [];
		$uri = '';
		switch($protocol) {
			case self::PROTOCOL_AUTO:
			case self::PROTOCOL_PATH_INFO:
				$uri = $this->parseRequestUri();
				break;
			case self::PROTOCOL_REQUEST_URI:
				$uri = $this->parseRequestUri();
				break;
			case self::PROTOCOL_QUERY_STRING:
				$uri = $this->parseQueryString();
				break;
		}
		$this->setUriString($uri);
	}

	public function getSegments()
	{
		return $this->_segments;
	}

	public function getSegment($n)
	{
		return isset($this->_segments[$n])?$this->_segments[$n]:null;
	}

	public function assoc2uri($array)
	{
		$temp = array();
		foreach ((array) $array as $key => $val){
			$temp[] = $key;
			$temp[] = $val;
		}
		return implode('/', $temp);
	}

	public function uri2assoc($n=3)
	{
		$retval = array();
		$totalSegments = count($this->_segments);
		if ($totalSegments < $n) {
			return $retval;
		}
		$segments = array_slice($this->_segments, ($n-1));
		$i = 0;
		$lastval = '';
		
		foreach($segments as $seg) {
			if ($i%2) {
				$retval[$lastval] = $seg;
			}else{
				$retval[$seg] = null;
				$lastval = $seg;
			}
			$i++;
		}
		return $retval;
	}

	public function getUrl()
	{
		return $this->_urlString;
	}

	private function parseRequestUri()
	{
		if (!isset($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME'])){
			return '';
		}
		$uri = parse_url('http://dummy'.$_SERVER['REQUEST_URI']);
		$query = isset($uri['query']) ? $uri['query'] : '';
		$uri = isset($uri['path']) ? $uri['path'] : '';

		if (isset($_SERVER['SCRIPT_NAME'][0])){
			if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0){
				$uri = (string) substr($uri, strlen($_SERVER['SCRIPT_NAME']));
			}elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0){
				$uri = (string) substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
			}
		}
		if (trim($uri, '/') === '' && strncmp($query, '/', 1) === 0){
			$query = explode('?', $query, 2);
			$uri = $query[0];
			$_SERVER['QUERY_STRING'] = isset($query[1]) ? $query[1] : '';
		}else{
			$_SERVER['QUERY_STRING'] = $query;
		}
		parse_str($_SERVER['QUERY_STRING'], $_GET);
		if ($uri === '/' OR $uri === ''){
			return '/';
		}
		return $this->removeRelativeDirectory($uri);
	}
	
	private function parseQueryString()
	{
		$uri = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : @getenv('QUERY_STRING');
		if (trim($uri, '/') === ''){
			return '';
		}elseif (strncmp($uri, '/', 1) === 0) {
			$uri = explode('?', $uri, 2);
			$_SERVER['QUERY_STRING'] = isset($uri[1]) ? $uri[1] : '';
			$uri = $uri[0];
		}
		parse_str($_SERVER['QUERY_STRING'], $_GET);
		return $this->removeRelativeDirectory($uri);
	}

	private function setUriString($uri)
	{
		$this->_urlString = trim($uri, '/');
		if ($this->_urlString != '') {
			foreach(explode('/', trim($this->_urlString, '/')) as $val) {
				$val = trim($val);
				$val = $this->filterUri($val);
				if ($val) {
					$this->_segments[] = $val;
				}
			}
		}
	}

	private function removeRelativeDirectory($uri)
	{
		$uris = array();
		$tok = strtok($uri, '/');
		while ($tok !== FALSE){
			if (( ! empty($tok) OR $tok === '0') && $tok !== '..'){
				$uris[] = $tok;
			}
			$tok = strtok('/');
		}
		return implode('/', $uris);
	}

	private function filterUri($str)
	{
		if (!empty($str) && preg_match('/^['.$this->_permittedUriChars.']+$/iu', $str)) {
			return $str;
		}else{
			return null;
		}
	}
}
