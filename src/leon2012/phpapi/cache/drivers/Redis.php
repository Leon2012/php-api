<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-28 09:35:08
 * @version $Id$
 */
namespace leon2012\phpapi\cache\drivers;

use leon2012\phpapi\cache\CacheInterface;
use leon2012\phpapi\exceptions\CoreException;
use leon2012\phpapi\exceptions\NotFoundMethodException;

class Redis extends Base implements CacheInterface
{
 
    private $_obj = null;
    private $_timeout = 60;
    private $_port = 6379;
    private $_pconnect = false;

    public function __construct($params = [])
    {
        if (!extension_loaded('redis')) {
            throw new CoreException('Cannot load redis extension');
        }
        $this->connect($params);
        parent::__construct('redis');
    }

    public function get($key)
    {
        return $this->_obj->get($key);
    }

    public function set($key, $value, $expireAt = 60)
    {
        return $this->_obj->set($key, $value, $expireAt);
    }

    public function exists($key)
    {
        return $this->_obj->exists($key);
    }

    public function delete($key)
    {
        $len = $this->_obj->delete($key);
        return ($len > 0 ? true : false);
    }

    public function clear()
    {
        throw new CoreException('Not implemented');
    }

    public function flush()
    {
        return $this->_obj->flushall();
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
            throw new CoreException('reids host empty');
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
        $this->_obj = new \Redis();
        if ($pconnect) {
            $ret = $this->_obj->pconnect($host, $port, $timeout);
        }else{
            $ret = $this->_obj->connect($host, $port, $timeout);
        }
        if (!$ret) {
            $this->_obj = null;
            throw new CoreException('cannot connect reids host');
        }
    }
}