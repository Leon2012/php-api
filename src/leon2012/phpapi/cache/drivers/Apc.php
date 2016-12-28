<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-19 11:53:07
 * @version $Id$
 */
namespace leon2012\phpapi\cache\drivers;

use leon2012\phpapi\cache\CacheInterface;
use leon2012\phpapi\exceptions\CoreException;

class Apc extends Base implements CacheInterface
{
    
    public function __construct()
    {
        if (!extension_loaded('apc')) {
            throw new CoreException('Cannot load APC extension');
        }
        parent::__construct('apc');
    }

    public function get($key)
    {
        return apc_fetch($key);
    }

    public function set($key, $value, $expireAt = 60)
    {
        return apc_add($key, $value, $expireAt);
    }

    public function exists($key)
    {
        return apc_exists($key);
    }

    public function delete($key)
    {
        return apc_delete($key);
    }

    public function clear()
    {
        return apc_clear_cache();
    }

    public function flush()
    {
        throw new CoreException('Not implemented');
    }
}