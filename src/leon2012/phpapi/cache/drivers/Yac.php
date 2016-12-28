<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-28 10:47:02
 * @version $Id$
 */
namespace leon2012\phpapi\cache\drivers;

use leon2012\phpapi\cache\CacheInterface;
use leon2012\phpapi\exceptions\CoreException;

class Yac extends Base implements CacheInterface
{

    private $_obj;

    public function __construct($params = [])
    {
        if (!extension_loaded('yac')) {
            throw new CoreException('Cannot load YAC extension');
        }
        if (isset($params['prefix'])) {
            $this->_obj = new \Yac($params['prefix']);
        }else{
            $this->_obj = new \Yac();
        }
        parent::__construct('yac');
    }

    public function get($key)
    {
        return $this->_obj->get($key);
    }

    public function set($key, $value, $expireAt = 60)
    {
        return $this->_obj->set($key, $value);
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
}