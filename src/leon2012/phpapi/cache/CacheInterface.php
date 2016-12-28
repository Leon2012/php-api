<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-16 15:57:29
 * @version $Id$
 */
namespace leon2012\phpapi\cache;

interface CacheInterface 
{
    public function get($key);
    public function set($key, $value, $expireAt = 60);
    public function exists($key);
    public function delete($key);
    public function clear(); 
    public function flush(); 
}