<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-01 10:18:31
 * @version $Id$
 */

namespace leon2012\phpapi;

use leon2012\phpapi\exceptions\CoreException;
use leon2012\phpapi\responses\JsonResponse;
use leon2012\phpapi\responses\XmlResponse;
use leon2012\phpapi\responses\JsonpResponse;

abstract class Response  
{
    
    protected $_ret;
    protected $_msg;
    protected $_data;
    protected $_contentType;
    private $_httpStatusCode;
    private $_headers;
    private $_format;
    private $_cache;
    private $_cacheTime;
    private $_charset;

    const FORMAT_JSON = 'JSON';
    const FORMAT_JSONP = 'JSONP';
    const FORMAT_XML = 'XML';


    
    public function __construct()
    {
        $this->_httpStatusCode = 200;
        $this->_ret = 200;
        $this->_msg = '';
        $this->_data = '';
        $this->_headers = [];
        $this->_cache = false;
        $this->_cacheTime = 120;//120 秒
        $this->_charset = 'utf-8';
    }

    public function setRet($ret)
    {
        $this->_ret = $ret;
        return $this;
    }

    public function setMsg($msg) 
    {
        $this->_msg = $msg;
        return $this;
    }

    public function setData($data)
    {
        if (is_null($data)) {
            $data = '';
        }
        $this->_data = $data;
        return $this;
    }

    public function setOutput($output = [])
    {
        if (isset($output['ret'])) {
            $this->setRet($output['ret']);
        }
        if (isset($output['msg'])) {
            $this->setMsg($output['msg']);
        }
        if (isset($output['data'])) {
            $this->setData($output['data']);
        }
    }

    public function addHeader($key, $value)
    {
        if (!empty($key)) {
            $this->_headers[$key] = $value;    
        }
    }

    public function enableCache($enable = false)
    {
        $this->_cache = $enable;
        return $this;
    }

    public function output()
    {
        $outputString = $this->encode();
        if (is_null($outputString)) {
            throw new CoreException("response encode failue");
        }
        http_response_code($this->_httpStatusCode);
        $contentType = sprintf("Content-Type: %s;  charset=%s", $this->_contentType, $this->_charset);
        header($contentType);
        if ($this->_cache) {//enable cache
            header('Cache-Control:max-age='.$this->_cacheTime, true);
            header('Pragma:public');
        }else{ //disable cache
            header('Expires: Thu, 01-Jan-70 00:00:01 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: no-store, no-cache, must-revalidate');
            header('Cache-Control: post-check=0, pre-check=0', false);
            header('Pragma: no-cache');
        }
        foreach($this->_headers as $key => $value) {
            $headerString = sprintf("%s: %s", $key, $value);
            header($headerString);
        }
        echo $outputString;
        exit;
    }

    private function __clone(){}

    abstract function encode();

    public static function outputFormats()
    {
        return [self::FORMAT_JSON, self::FORMAT_XML, self::FORMAT_JSONP];
    }

    public static function create($format = self::FORMAT_JSON)
    {
        $instance = null;
        switch($format){
            case self::FORMAT_JSON:
                $instance = new JsonResponse();
            break;

            case self::FORMAT_XML:
                $instance = new XmlResponse();
            break;

            case self::FORMAT_JSONP:
                $instance = new JsonpResponse();
            break;
        }
        return $instance;
    }
}