<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-28 09:05:03
 * @version $Id$
 */
namespace leon2012\phpapi\cache\drivers;

use leon2012\phpapi\cache\CacheInterface;
use leon2012\phpapi\exceptions\CoreException;
use leon2012\phpapi\exceptions\NotFoundMethodException;

class Memcache extends Base implements CacheInterface
{
    
    private $_obj = null;
    private $_timeout = 60;
    private $_port = 11211;
    private $_pconnect = false;
    private $_flag = 0;

    public function __construct($params = [])
    {
        if (!extension_loaded('memcache')) {
            throw new CoreException('Cannot load memcache extension');
        }
        $this->connect($params);
        parent::__construct('memcache');
    }

    public function get($key)
    {
        return $this->_obj->get($key);
    }

    public function set($key, $value, $expireAt = 60)
    {
        return $this->_obj->set($key, $value, 0, $expireAt);
    }

    public function exists($key)
    {
        $val = $this->get($key);
        if ($val === false) {
            return false;
        }else{
            return true;
        }
    }

    public function delete($key)
    {
        return $this->_obj->delete($key);
    }

    public function clear()
    {
        throw new CoreException('Not implemented');
    }

    public function flush()
    {
        return $this->_obj->flush();
    }

    public function __call($name, $arguments = null)
    {
        if (!method_exists($this->_obj, $name)) {
            throw new NotFoundMethodException($name);
        }
        $args = [];
        if (!is_null($arguments)) {
            if (!is_array($arguments)) {
                $args[0] = $arguments;
            } else {
                $args = $arguments;
            }
        }
        return call_user_func_array([$this->_obj, $name], $args);
    }

    private function connect($params)
    {
        if (!isset($params['host']) || empty($params['host'])) {
            throw new CoreException('memcache host empty');
        }
        $host = $params['host'];
        if (!isset($params['port'])) {
            $port = $this->_port;
        }else{
            $port = $params['port'];
        }
        if (!isset($params['timeout'])) {
            $timeout = $this->_timeout;
        }else{
            $timeout = $params['timeout'];
        }
        if (!isset($params['pconnect'])) {
            $pconnect = $this->_pconnect;
        }else{
            $pconnect = $params['pconnect'];
        }
        $this->_obj = new \Memcache;
        if ($pconnect) {
            $ret = $this->_obj->pconnect($host, $port, $timeout);
        }else{
            $ret = $this->_obj->connect($host, $port, $timeout);
        }
        if (!$ret) {
            $this->_obj = null;
            throw new CoreException('cannot connect memcache host');
        }
    }
}