<?php
/**
 *
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */

namespace leon2012\phpapi;

use leon2012\phpapi\collections\ArrayCollection;
use leon2012\phpapi\collections\HeaderCollection;

class Request
{

    private $_headers;
    private $_cookie;
    private $_get;
    private $_post;
    private $_request;
    private $_server;

    private $_rawBody;
    private $_pathInfo;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->sanitizeGlobals();
        $this->_headers = new HeaderCollection();
        $this->_cookies = new ArrayCollection($_COOKIE);
        $this->_get = new ArrayCollection($_GET);
        $this->_post = new ArrayCollection($_POST);
        $this->_request = new ArrayCollection($_REQUEST);
        $this->_server = new ArrayCollection($_SERVER);
    }

    /**
     * @param $name
     * @param  string     $defaultValue
     * @return mixed|null
     */
    public function get($name, $defaultValue = '')
    {
        return $this->_get->get($name, $defaultValue);
    }

    /**
     * @param $name
     * @param  string     $defaultValue
     * @return mixed|null
     */
    public function post($name, $defaultValue = '')
    {
        return $this->_post->get($name, $defaultValue);
    }

    /**
     * @param $name
     * @param  string     $defaultValue
     * @return mixed|null
     */
    public function request($name, $defaultValue = '')
    {
        return $this->_request->get($name, $defaultValue);
    }

    /**
     * @param $name
     * @param  string     $defaultValue
     * @return mixed|null
     */
    public function server($name, $defaultValue = '')
    {
        return $this->_server->get($name, $defaultValue);
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
            return strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
        }
        if (isset($_SERVER['REQUEST_METHOD'])) {
            return strtoupper($_SERVER['REQUEST_METHOD']);
        }

        return 'GET';
    }

    /**
     * @return bool
     */
    public function isPost()
    {
        return $this->getMethod() == 'POST';
    }

    /**
     * @return bool
     */
    public function isGet()
    {
        return $this->getMethod() == 'GET';
    }

    /**
     * @return bool
     */
    public function isOptions()
    {
        return $this->getMethod() == 'OPTIONS';
    }

    /**
     * @return bool
     */
    public function isHead()
    {
        return $this->getMethod() == 'HEAD';
    }

    /**
     * @return bool
     */
    public function isDelete()
    {
        return $this->getMethod() == 'DELETE';
    }

    /**
     * @return bool
     */
    public function isPut()
    {
        return $this->getMethod() == 'PUT';
    }

    /**
     * @return bool
     */
    public function isPatch()
    {
        return $this->getMethod() == 'PATCH';
    }

    /**
     * @return bool
     */
    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    /**
     * @return bool
     */
    public function isAjaxp()
    {
        return $this->isAjax() && !empty($_SERVER['HTTP_X_PJAX']);
    }

    /**
     * @return bool
     */
    public function isFlash()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) &&
            (stripos($_SERVER['HTTP_USER_AGENT'], 'Shockwave') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'Flash') !== false);

    }

    /**
     * @return string
     */
    public function getRawBody()
    {
        if ($this->_rawBody == null) {
            $this->_rawBody = file_get_contents('php://input');
        }

        return $this->_rawBody;
    }

    /**
     * @return mixed
     */
    public function getPathInfo()
    {
        return $this->_pathInfo;
    }

    /**
     * @param $pathInfo
     * @return $this
     */
    public function setPathInfo($pathInfo)
    {
        $this->_pathInfo = $pathInfo;

        return $this;
    }

    /**
     * @return string
     */
    public function getQueryString()
    {
        return isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
    }

    /**
     * @return null
     */
    public function getServerName()
    {
        return isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : null;
    }

    /**
     * @return int|null
     */
    public function getServerPort()
    {
        return isset($_SERVER['SERVER_PORT']) ? (int) $_SERVER['SERVER_PORT'] : null;
    }

    /**
     * @return null
     */
    public function getReferrer()
    {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
    }

    /**
     * @return null
     */
    public function getUserAgent()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
    }

    /**
     * @return null
     */
    public function getUserHost()
    {
        return isset($_SERVER['REMOTE_HOST']) ? $_SERVER['REMOTE_HOST'] : null;
    }
    
    private function sanitizeGlobals()
    {
        if (is_array($_GET)) {
            foreach($_GET as $key => $val) {
                $_GET[$this->cleanInputKey($key)] = $this->cleanInputValue($val);
            }
        }
        if (is_array($_POST)) {
            foreach($_POST as $key => $val) {
                $_POST[$this->cleanInputKey($key)] = $this->cleanInputValue($val);
            }
        }
        $_SERVER['PHP_SELF'] = strip_tags($_SERVER['PHP_SELF']);
    }

    private function cleanInputKey($str, $fatal = TRUE)
	{
		if ( ! preg_match('/^[a-z0-9:_\/|-]+$/i', $str)){
			if ($fatal === TRUE){
				return FALSE;
			}else{
				set_status_header(503);
				echo 'Disallowed Key Characters.';
				exit(7); // EXIT_USER_INPUT
			}
		}
		return $str;
    }
    
    private function cleanInputValue($str)
	{
		if (is_array($str)){
			$new_array = array();
			foreach (array_keys($str) as $key){
				$new_array[$this->cleanInputKey($key)] = $this->cleanInputValue($str[$key]);
			}
			return $new_array;
		}
		if (get_magic_quotes_gpc()){
			$str = stripslashes($str);
        }
        $str = Security::removeInvisibleCharacters($str, FALSE);
		return $str;
    }
}
